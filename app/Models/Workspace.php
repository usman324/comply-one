<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Workspace extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'name',
        'workspace_number',
        'description',
        'type',
        'status',
        'avatar',
        'owner_id',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    /**
     * Many-to-Many: Workspace has many Questions
     */
    public function questions()
    {
        return $this->belongsToMany(Question::class, 'workspace_questions')
            ->withPivot(['order', 'is_required', 'workspace_specific_options'])
            ->withTimestamps()
            ->orderBy('workspace_questions.order');
    }

    /**
     * Get questions grouped by section
     */
    public function questionsBySection()
    {
        return $this->questions()
            ->with('section')
            ->get()
            ->groupBy(function ($question) {
                return $question->section ? $question->section->name : 'Uncategorized';
            });
    }

    /**
     * Get required questions for this workspace
     */

    public static function generateWorkspaceNumber()
    {
        $prefix = 'COMPLYONE';

        // Get the last workspace number
        $lastWorkspace = self::withTrashed()
            ->where('workspace_number', 'like', "{$prefix}-%")
            ->orderBy('workspace_number', 'desc')
            ->first();

        if ($lastWorkspace) {
            // Extract the sequence number and increment
            $lastNumber = (int) substr($lastWorkspace->workspace_number, strlen($prefix) + 1);
            $newNumber = $lastNumber + 1;
        } else {
            // Start with 1
            $newNumber = 1;
        }

        // Format: COMPLYONE-000001
        return sprintf('%s-%06d', $prefix, $newNumber);
    }
    public function requiredQuestions()
    {
        return $this->questions()
            ->wherePivot('is_required', true)
            ->orWhere('questions.is_required', true);
    }

    /**
     * Check if workspace has a specific question
     */
    public function hasQuestion($questionId)
    {
        return $this->questions()->where('question_id', $questionId)->exists();
    }

    /**
     * Attach questions to workspace with order
     */
    public function attachQuestions(array $questionIds, $startOrder = 0)
    {
        $data = [];
        foreach ($questionIds as $index => $questionId) {
            $data[$questionId] = [
                'order' => $startOrder + $index,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }
        $this->questions()->syncWithoutDetaching($data);
    }

    /**
     * Sync questions with order and settings
     */
    public function syncQuestionsWithSettings(array $questions)
    {
        $syncData = [];
        foreach ($questions as $index => $questionData) {
            $questionId = is_array($questionData) ? $questionData['id'] : $questionData;
            $syncData[$questionId] = [
                'order' => $index,
                'is_required' => $questionData['is_required'] ?? false,
                'workspace_specific_options' => isset($questionData['options']) ? json_encode($questionData['options']) : null,
            ];
        }
        $this->questions()->sync($syncData);
    }

    /**
     * Questionnaire Responses
     */
    public function questionnaireResponses()
    {
        return $this->hasMany(QuestionnaireResponse::class);
    }

    /**
     * Get response for a specific question
     */
    public function getQuestionResponse($questionId)
    {
        return $this->questionnaireResponses()
            ->where('question_id', $questionId)
            ->first();
    }

    /**
     * Get all responses grouped by section
     */
    public function getResponsesBySection()
    {
        $responses = $this->questionnaireResponses()->with('question.section')->get();

        return $responses->groupBy(function ($response) {
            return $response->question->section
                ? $response->question->section->name
                : 'Uncategorized';
        });
    }

    /**
     * Calculate completion percentage
     */
    public function getCompletionPercentage()
    {
        $totalQuestions = $this->questions()->count();
        if ($totalQuestions === 0) {
            return 0;
        }

        $answeredQuestions = $this->questionnaireResponses()
            ->whereIn('question_id', $this->questions()->pluck('id'))
            ->count();

        return round(($answeredQuestions / $totalQuestions) * 100, 2);
    }

    /**
     * Check if all required questions are answered
     */
    public function hasCompletedRequiredQuestions()
    {
        $requiredQuestions = $this->requiredQuestions()->pluck('id');
        $answeredRequired = $this->questionnaireResponses()
            ->whereIn('question_id', $requiredQuestions)
            ->count();

        return $answeredRequired === $requiredQuestions->count();
    }

    /**
     * Owner relationship
     */
    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    /**
     * Creator relationship
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Updater relationship
     */
    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    /**
     * Creator relationship
     */
    public function users()
    {
        return $this->hasMany(User::class, 'workspace_id', 'id');
    }

    /**
     * Get avatar URL
     */
    public function getAvatarUrl()
    {
        if ($this->avatar) {
            return asset('storage/workspace/' . $this->avatar);
        }
        return asset('images/default-workspace.png');
    }

    /**
     * Get type badge HTML
     */
    public function getTypeBadge()
    {
        $badges = [
            'personal' => '<span class="badge bg-info-subtle text-info">Personal</span>',
            'team' => '<span class="badge bg-primary-subtle text-primary">Team</span>',
            'enterprise' => '<span class="badge bg-purple-subtle text-purple">Enterprise</span>',
        ];

        return $badges[$this->type] ?? '<span class="badge bg-secondary">Unknown</span>';
    }

    /**
     * Get status badge HTML
     */
    public function getStatusBadge()
    {
        $badges = [
            'active' => '<span class="badge bg-success">Active</span>',
            'inactive' => '<span class="badge bg-danger">Inactive</span>',
            'pending' => '<span class="badge bg-warning">Pending</span>',
        ];

        return $badges[$this->status] ?? '<span class="badge bg-secondary">Unknown</span>';
    }

    /**
     * Scope: Active workspaces
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope: By type
     */
    public function scopeOfType($query, $type)
    {
        return $query->where('type', $type);
    }

    /**
     * Scope: By owner
     */
    public function scopeOwnedBy($query, $userId)
    {
        return $query->where('owner_id', $userId);
    }
}
