<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Question extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'questionnaire_id',
        'question',
        'order',
        'description',
        'type',
        'options',
        'is_required',
    ];

    /**
     * Get the questionnaire that owns the question.
     */
    public function questionnaire()
    {
        return $this->belongsTo(Questionnaire::class);
    }

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'options' => 'array',
        'depends_on' => 'array',
        'is_required' => 'boolean',
    ];

    /**
     * Check if this question depends on another question.
     */
    public function hasDependency(): bool
    {
        return !empty($this->depends_on);
    }

    /**
     * Get the question this question depends on.
     */
    public function getDependencyQuestionId(): ?string
    {
        return $this->depends_on['question_id'] ?? null;
    }

    /**
     * Get the required value for the dependency.
     */
    public function getDependencyValue()
    {
        return $this->depends_on['value'] ?? null;
    }

    /**
     * Check if the dependency is satisfied based on a given answer.
     */
    public function isDependencySatisfied($answer): bool
    {
        if (!$this->hasDependency()) {
            return true;
        }

        $requiredValue = $this->getDependencyValue();

        // Handle array values (for checkbox dependencies)
        if (is_array($requiredValue)) {
            if (is_array($answer)) {
                return !empty(array_intersect($requiredValue, $answer));
            }
            return in_array($answer, $requiredValue);
        }

        // Handle single value
        if (is_array($answer)) {
            return in_array($requiredValue, $answer);
        }

        return $answer === $requiredValue;
    }
}
