<?php

namespace App\Services;

use App\Models\Questionnaire;
use App\Models\QuestionnaireResponse;
use App\Models\UserQuestionnaireSession;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Request;

class QuestionnaireService
{
    /**
     * Save or update a user's response to a question.
     */
    public function saveResponse(int $userId, string $questionId, $answer): QuestionnaireResponse
    {
        $questionnaire = Questionnaire::where('question_id', $questionId)->firstOrFail();

        return DB::transaction(function () use ($userId, $questionnaire, $questionId, $answer) {
            // Create or update response
            $response = QuestionnaireResponse::updateOrCreate(
                [
                    'user_id' => $userId,
                    'questionnaire_id' => $questionnaire->id,
                    'question_id' => $questionId,
                ],
                [
                    'section' => $questionnaire->section,
                    'answer' => $answer,
                    'answered_at' => now(),
                    'ip_address' => Request::ip(),
                    'user_agent' => Request::userAgent(),
                ]
            );

            // Update session progress
            $session = UserQuestionnaireSession::getOrCreate($userId, $questionnaire->section);
            $session->updateProgress();

            return $response;
        });
    }

    /**
     * Save multiple responses at once.
     */
    public function saveMultipleResponses(int $userId, array $responses): array
    {
        return DB::transaction(function () use ($userId, $responses) {
            $savedResponses = [];

            foreach ($responses as $questionId => $answer) {
                $savedResponses[] = $this->saveResponse($userId, $questionId, $answer);
            }

            return $savedResponses;
        });
    }

    /**
     * Get user's response for a specific question.
     */
    public function getUserResponse(int $userId, string $questionId): ?QuestionnaireResponse
    {
        return QuestionnaireResponse::findByUserAndQuestion($userId, $questionId);
    }

    /**
     * Get all responses for a user in a specific section.
     */
    public function getUserSectionResponses(int $userId, string $section): array
    {
        $responses = QuestionnaireResponse::getUserSectionResponses($userId, $section);

        return $responses->mapWithKeys(function ($response) {
            return [$response->question_id => $response->answer];
        })->toArray();
    }

    /**
     * Get questions for a section with user's answers.
     */
    public function getSectionQuestionsWithAnswers(int $userId, string $section): array
    {
        $questions = Questionnaire::getBySection($section);
        $responses = $this->getUserSectionResponses($userId, $section);

        return $questions->map(function ($question) use ($responses) {
            return [
                'question' => $question,
                'answer' => $responses[$question->question_id] ?? null,
            ];
        })->toArray();
    }

    /**
     * Check if user has completed a section.
     */
    public function isSectionCompleted(int $userId, string $section): bool
    {
        $session = UserQuestionnaireSession::forUser($userId)
            ->bySection($section)
            ->first();

        return $session ? $session->isCompleted() : false;
    }

    /**
     * Get completion percentage for a section.
     */
    public function getSectionCompletionPercentage(int $userId, string $section): float
    {
        $session = UserQuestionnaireSession::getOrCreate($userId, $section);
        $session->updateProgress();

        return $session->completion_percentage;
    }

    /**
     * Get overall progress for all sections.
     */
    public function getOverallProgress(int $userId): array
    {
        $sections = Questionnaire::distinct('section')->pluck('section');

        $progress = [];
        foreach ($sections as $section) {
            $session = UserQuestionnaireSession::getOrCreate($userId, $section);
            $session->updateProgress();

            $progress[$section] = [
                'status' => $session->status,
                'total_questions' => $session->total_questions,
                'answered_questions' => $session->answered_questions,
                'completion_percentage' => $session->completion_percentage,
                'started_at' => $session->started_at,
                'completed_at' => $session->completed_at,
            ];
        }

        return $progress;
    }

    /**
     * Delete a user's response.
     */
    public function deleteResponse(int $userId, string $questionId): bool
    {
        return DB::transaction(function () use ($userId, $questionId) {
            $response = QuestionnaireResponse::findByUserAndQuestion($userId, $questionId);

            if ($response) {
                $section = $response->section;
                $deleted = $response->delete();

                // Update session progress
                $session = UserQuestionnaireSession::getOrCreate($userId, $section);
                $session->updateProgress();

                return $deleted;
            }

            return false;
        });
    }

    /**
     * Get questions that should be shown based on dependencies.
     */
    public function getVisibleQuestions(int $userId, string $section): array
    {
        $questions = Questionnaire::getBySection($section);
        $responses = $this->getUserSectionResponses($userId, $section);

        return $questions->filter(function ($question) use ($responses) {
            if (!$question->hasDependency()) {
                return true;
            }

            $dependencyQuestionId = $question->getDependencyQuestionId();
            $dependencyAnswer = $responses[$dependencyQuestionId] ?? null;

            return $question->isDependencySatisfied($dependencyAnswer);
        })->values()->toArray();
    }

    /**
     * Validate if all required questions in a section are answered.
     */
    public function validateSectionCompletion(int $userId, string $section): array
    {
        $questions = $this->getVisibleQuestions($userId, $section);
        $responses = $this->getUserSectionResponses($userId, $section);

        $missingRequired = [];

        foreach ($questions as $question) {
            if ($question['required']) {
                $answer = $responses[$question['question_id']] ?? null;

                if (is_null($answer) || (is_array($answer) && empty($answer)) || (is_string($answer) && trim($answer) === '')) {
                    $missingRequired[] = $question['question_id'];
                }
            }
        }

        return [
            'is_valid' => empty($missingRequired),
            'missing_questions' => $missingRequired,
        ];
    }

    /**
     * Export user's responses for a section.
     */
    public function exportSectionResponses(int $userId, string $section): array
    {
        $responses = QuestionnaireResponse::getUserSectionResponses($userId, $section);

        return $responses->map(function ($response) {
            return [
                'question_id' => $response->question_id,
                'question_title' => $response->questionnaire->title,
                'question' => $response->questionnaire->message,
                'answer' => $response->getFormattedAnswer(),
                'answered_at' => $response->answered_at?->toDateTimeString(),
            ];
        })->toArray();
    }
}
