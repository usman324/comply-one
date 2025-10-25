<?php

namespace App\Models\Traits;

use App\Models\QuestionnaireResponse;
use App\Models\UserQuestionnaireSession;
use Illuminate\Database\Eloquent\Relations\HasMany;

trait HasQuestionnaires
{
    /**
     * Get all questionnaire responses for the user.
     */
    public function questionnaireResponses(): HasMany
    {
        return $this->hasMany(QuestionnaireResponse::class);
    }

    /**
     * Get all questionnaire sessions for the user.
     */
    public function questionnaireSessions(): HasMany
    {
        return $this->hasMany(UserQuestionnaireSession::class);
    }

    /**
     * Get responses for a specific section.
     */
    public function getSectionResponses(string $section)
    {
        return $this->questionnaireResponses()
            ->bySection($section)
            ->with('questionnaire')
            ->get();
    }

    /**
     * Get session for a specific section.
     */
    public function getSectionSession(string $section)
    {
        return $this->questionnaireSessions()
            ->bySection($section)
            ->first();
    }

    /**
     * Check if user has completed a section.
     */
    public function hasCompletedSection(string $section): bool
    {
        $session = $this->getSectionSession($section);
        return $session ? $session->isCompleted() : false;
    }

    /**
     * Get overall questionnaire completion percentage.
     */
    public function getQuestionnaireCompletionPercentage(): float
    {
        $sessions = $this->questionnaireSessions;

        if ($sessions->isEmpty()) {
            return 0;
        }

        $totalPercentage = $sessions->sum('completion_percentage');
        return $totalPercentage / $sessions->count();
    }
}
