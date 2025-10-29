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
    protected $fillable = [
        'user_id',
        'title',
        'section_id',
        'description',
        'start_date',
        'end_date',
        'allow_anonymous',
        'allow_multiple_responses',
        'show_progress',
        'randomize_questions',
        'status',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'allow_anonymous' => 'boolean',
        'allow_multiple_responses' => 'boolean',
        'show_progress' => 'boolean',
        'randomize_questions' => 'boolean',
    ];

    /**
     * Get the responses for this questionnaire.
     */
    public function responses()
    {
        return $this->hasMany(QuestionnaireResponse::class);
    }

    public function questions()
    {
        return $this->hasMany(Question::class);
    }
    public function section()
    {
        return $this->belongsTo(Section::class, 'section_id', 'id');
    }

    /**
     * Scope a query to only include questionnaires from a specific section.
     */
    public function scopeBySection($query, string $section)
    {
        return $query->where('section', $section);
    }
}
