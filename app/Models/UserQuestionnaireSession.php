<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserQuestionnaireSession extends Model
{
    use HasFactory;
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'section',
        'status',
        'total_questions',
        'answered_questions',
        'completion_percentage',
        'started_at',
        'completed_at',
        'metadata',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'total_questions' => 'integer',
        'answered_questions' => 'integer',
        'completion_percentage' => 'decimal:2',
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
        'metadata' => 'array',
    ];

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($session) {
            if (is_null($session->started_at) && $session->status !== 'not_started') {
                $session->started_at = now();
            }
        });
    }

    /**
     * Get the user that owns the session.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope a query to only include sessions for a specific user.
     */
    public function scopeForUser($query, int $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Scope a query to only include sessions for a specific section.
     */
    public function scopeBySection($query, string $section)
    {
        return $query->where('section', $section);
    }

    /**
     * Scope a query to only include completed sessions.
     */
    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    /**
     * Scope a query to only include in-progress sessions.
     */
    public function scopeInProgress($query)
    {
        return $query->where('status', 'in_progress');
    }

    /**
     * Update the session progress.
     */
    public function updateProgress(): void
    {
        $totalQuestions = Questionnaire::bySection($this->section)->count();
        $answeredQuestions = QuestionnaireResponse::forUser($this->user_id)
            ->bySection($this->section)
            ->whereNotNull('answer')
            ->count();

        $this->total_questions = $totalQuestions;
        $this->answered_questions = $answeredQuestions;
        $this->completion_percentage = $totalQuestions > 0
            ? ($answeredQuestions / $totalQuestions) * 100
            : 0;

        // Update status
        if ($answeredQuestions === 0) {
            $this->status = 'not_started';
        } elseif ($answeredQuestions >= $totalQuestions) {
            $this->status = 'completed';
            if (is_null($this->completed_at)) {
                $this->completed_at = now();
            }
        } else {
            $this->status = 'in_progress';
            if (is_null($this->started_at)) {
                $this->started_at = now();
            }
        }

        $this->save();
    }

    /**
     * Mark session as started.
     */
    public function markAsStarted(): void
    {
        if ($this->status === 'not_started') {
            $this->status = 'in_progress';
            $this->started_at = now();
            $this->save();
        }
    }

    /**
     * Mark session as completed.
     */
    public function markAsCompleted(): void
    {
        $this->status = 'completed';
        $this->completed_at = now();
        $this->completion_percentage = 100;
        $this->save();
    }

    /**
     * Check if session is completed.
     */
    public function isCompleted(): bool
    {
        return $this->status === 'completed';
    }

    /**
     * Check if session is in progress.
     */
    public function isInProgress(): bool
    {
        return $this->status === 'in_progress';
    }

    /**
     * Get or create a session for a user and section.
     */
    public static function getOrCreate(int $userId, string $section): self
    {
        return static::firstOrCreate(
            [
                'user_id' => $userId,
                'section' => $section,
            ],
            [
                'status' => 'not_started',
                'total_questions' => Questionnaire::bySection($section)->count(),
                'answered_questions' => 0,
                'completion_percentage' => 0,
            ]
        );
    }

    /**
     * Get all sessions for a user with progress.
     */
    public static function getUserProgress(int $userId)
    {
        return static::forUser($userId)->get();
    }
}
