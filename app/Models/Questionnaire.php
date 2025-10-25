<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Questionnaire extends Model
{
    use HasFactory;
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $guarded = [];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'options' => 'array',
        'depends_on' => 'array',
        'required' => 'boolean',
    ];

    /**
     * Get the responses for this questionnaire.
     */
    public function responses()
    {
        return $this->hasMany(QuestionnaireResponse::class);
    } public function questions()
    {
        return $this->hasMany(Question::class);
    }

    /**
     * Scope a query to only include questions from a specific section.
     */
    public function scopeBySection($query, string $section)
    {
        return $query->where('section', $section);
    }

    /**
     * Scope a query to only include required questions.
     */
    public function scopeRequired($query)
    {
        return $query->where('required', true);
    }

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

    /**
     * Get questions by section in order.
     */
    public static function getBySection(string $section)
    {
        return static::bySection($section)->orderBy('id')->get();
    }
}
