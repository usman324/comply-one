<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class QuestionnaireResponse extends Model
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
        'questionnaire_id',
        'section',
        'question_id',
        'answer',
        'answer_text',
        'answered_at',
        'ip_address',
        'user_agent',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'answer' => 'array',
        'answered_at' => 'datetime',
    ];

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($response) {
            // Automatically set answered_at timestamp
            if (is_null($response->answered_at)) {
                $response->answered_at = now();
            }

            // Convert array answers to searchable text
            if (is_array($response->answer)) {
                $response->answer_text = implode(', ', $response->answer);
            } else {
                $response->answer_text = $response->answer;
            }
        });

        static::updating(function ($response) {
            // Update answer_text when answer changes
            if ($response->isDirty('answer')) {
                if (is_array($response->answer)) {
                    $response->answer_text = implode(', ', $response->answer);
                } else {
                    $response->answer_text = $response->answer;
                }
            }
        });
    }

    /**
     * Get the user that owns the response.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the questionnaire that this response belongs to.
     */
    public function questionnaire(): BelongsTo
    {
        return $this->belongsTo(Questionnaire::class);
    }

    /**
     * Scope a query to only include responses for a specific user.
     */
    public function scopeForUser($query, int $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Scope a query to only include responses for a specific section.
     */
    public function scopeBySection($query, string $section)
    {
        return $query->where('section', $section);
    }

    /**
     * Scope a query to only include responses for a specific question.
     */
    public function scopeByQuestion($query, string $questionId)
    {
        return $query->where('question_id', $questionId);
    }

    /**
     * Get response by user and question ID.
     */
    public static function findByUserAndQuestion(int $userId, string $questionId)
    {
        return static::forUser($userId)->byQuestion($questionId)->first();
    }

    /**
     * Get all responses for a user's section.
     */
    public static function getUserSectionResponses(int $userId, string $section)
    {
        return static::forUser($userId)
            ->bySection($section)
            ->with('questionnaire')
            ->orderBy('created_at')
            ->get();
    }

    /**
     * Check if the answer is empty.
     */
    public function isEmpty(): bool
    {
        if (is_null($this->answer)) {
            return true;
        }

        if (is_array($this->answer)) {
            return empty($this->answer);
        }

        return trim($this->answer) === '';
    }

    /**
     * Get the answer as a formatted string.
     */
    public function getFormattedAnswer(): string
    {
        if (is_array($this->answer)) {
            return implode(', ', $this->answer);
        }

        return $this->answer ?? '';
    }
}
