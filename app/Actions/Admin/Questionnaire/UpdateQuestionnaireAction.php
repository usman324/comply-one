<?php

namespace App\Actions\Admin\Questionnaire;

use App\Actions\BaseAction;
use App\Models\Question;
use App\Models\Questionnaire;
use App\Traits\CustomAction;
use Lorisleiva\Actions\ActionRequest;
use App\Traits\RespondsWithJson;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsAction;

class UpdateQuestionnaireAction extends BaseAction
{
    use AsAction;
    use RespondsWithJson;
    use CustomAction;

    protected string $title = 'Questionnaire';
    protected string $view = 'admin.questionnaire';
    protected string $url = 'questionnaires';
    protected string $permission = 'questionnaire';

    public function rules(ActionRequest $request): array
    {

        return [
            'title' => 'required|string|max:255',
            'section_id' => 'required|string',
            'description' => 'nullable|string',
            'status' => 'required|in:active,inactive',
            'questions' => 'required|json',
            'force_update' => 'nullable|boolean', // Allow forcing update
            'move_to_existing' => 'nullable|boolean', // Allow moving questions
        ];
    }
    public function handle(
        $request,
        int $id
    ) {
        DB::beginTransaction();
        try {
            $questionnaire = Questionnaire::findOrFail($id);
            $oldSectionId = $questionnaire->section_id;
            $newSectionId = $request->section_id;

            // Check if section is changing
            $sectionChanged = $oldSectionId != $newSectionId;
            // If section changed, check if another questionnaire exists for new section
            if ($sectionChanged) {
                $existingQuestionnaire = Questionnaire::where('section_id', $newSectionId)
                    ->where('id', '!=', $id)
                    ->where('status', 'active')
                    ->first();

                // If exists and user hasn't confirmed action, ask for confirmation
                if ($existingQuestionnaire && !$request->force_update && !$request->move_to_existing) {
                    DB::rollBack();

                    return response()->json([
                        'success' => false,
                        'needs_confirmation' => true,
                        'message' => 'A questionnaire for this section already exists.',
                        'existing_questionnaire' => [
                            'id' => $existingQuestionnaire->id,
                            'title' => $existingQuestionnaire->title,
                            'questions_count' => $existingQuestionnaire->questions()->count(),
                            'created_at' => $existingQuestionnaire->created_at->format('M d, Y')
                        ],
                        'current_questionnaire' => [
                            'id' => $questionnaire->id,
                            'title' => $questionnaire->title,
                            'questions_count' => $questionnaire->questions()->count(),
                        ],
                        'options' => [
                            'move_to_existing' => 'Move questions to existing questionnaire and delete this one',
                            'force_update' => 'Keep both questionnaires (not recommended)'
                        ]
                    ], 409);
                }

                // User chose to move questions to existing questionnaire
                if ($existingQuestionnaire && $request->move_to_existing) {
                    $questions = json_decode($request->questions, true);

                    // Get the last order number for existing questionnaire
                    $lastOrder = Question::where('questionnaire_id', $existingQuestionnaire->id)
                        ->max('order') ?? 0;

                    // Get existing question IDs from current questionnaire
                    $existingQuestionIds = $questionnaire->questions()->pluck('id')->toArray();
                    $movedQuestionIds = [];

                    // Move/update questions to existing questionnaire
                    foreach ($questions as $index => $questionData) {
                        if (isset($questionData['id']) && in_array($questionData['id'], $existingQuestionIds)) {
                            // Update existing question and move to new questionnaire
                            $question = Question::find($questionData['id']);
                            if ($question) {
                                $question->update([
                                    'questionnaire_id' => $existingQuestionnaire->id,
                                    'question' => $questionData['question'],
                                    'type' => $questionData['type'],
                                    'description' => $questionData['description'] ?? null,
                                    'options' => isset($questionData['options']) ? json_encode($questionData['options']) : null,
                                    'is_required' => $questionData['is_required'] ?? false,
                                    'order' => $lastOrder + $index + 1
                                ]);
                                $movedQuestionIds[] = $question->id;
                            }
                        } else {
                            // Create new question in existing questionnaire
                            $question = Question::create([
                                'questionnaire_id' => $existingQuestionnaire->id,
                                'question' => $questionData['question'],
                                'type' => $questionData['type'],
                                'description' => $questionData['description'] ?? null,
                                'options' => isset($questionData['options']) ? json_encode($questionData['options']) : null,
                                'is_required' => $questionData['is_required'] ?? false,
                                'order' => $lastOrder + $index + 1
                            ]);
                            $movedQuestionIds[] = $question->id;
                        }
                    }

                    // Delete any questions that weren't moved
                    $questionsToDelete = array_diff($existingQuestionIds, $movedQuestionIds);
                    if (!empty($questionsToDelete)) {
                        Question::whereIn('id', $questionsToDelete)->delete();
                    }

                    // Delete the old questionnaire
                    $questionnaire->delete();

                    DB::commit();

                    return response()->json([
                        'success' => true,
                        'message' => 'Questions moved to existing section questionnaire successfully!',
                        'data' => [
                            'questionnaire_id' => $existingQuestionnaire->id,
                            'questions_moved' => count($questions),
                            'old_deleted' => true,
                            'redirect_to' => route('questionnaires.edit', $existingQuestionnaire->id)
                        ]
                    ]);
                }
            }
            // Normal update flow (no section change or force_update = true)

            // Update questionnaire basic info
            $questionnaire->update([
                'title' => $request->title,
                'section_id' => $request->section_id,
                'description' => $request->description,
                'allow_anonymous' => $request->has('allow_anonymous'),
                'allow_multiple_responses' => $request->has('allow_multiple_responses'),
                'show_progress' => $request->has('show_progress'),
                'randomize_questions' => $request->has('randomize_questions'),
                'status' => $request->status,
            ]);
            // Parse questions
            $questions = json_decode($request->questions, true);
            // Get existing question IDs
            $existingQuestionIds = $questionnaire->questions()->pluck('id')->toArray();
            $updatedQuestionIds = [];

            // Update or create questions
            foreach ($questions as $index => $questionData) {
                if (isset($questionData['id']) && in_array($questionData['id'], $existingQuestionIds)) {
                    // Update existing question
                    $question = Question::find($questionData['id']);
                    $question->update([
                        'question' => $questionData['question'],
                        'type' => $questionData['type'],
                        'description' => $questionData['description'] ?? null,
                        'options' => isset($questionData['options']) ? json_encode($questionData['options']) : null,
                        'is_required' => $questionData['is_required'] ?? false,
                        'order' => $index + 1
                    ]);
                    $updatedQuestionIds[] = $question->id;
                } else {
                    // Create new question
                    $question = Question::create([
                        'questionnaire_id' => $questionnaire->id,
                        'question' => $questionData['question'],
                        'type' => $questionData['type'],
                        'description' => $questionData['description'] ?? null,
                        'options' => isset($questionData['options']) ? json_encode($questionData['options']) : null,
                        'is_required' => $questionData['is_required'] ?? false,
                        'order' => $index + 1
                    ]);
                    $updatedQuestionIds[] = $question->id;
                }
            }
            // Delete questions that were removed
            $questionsToDelete = array_diff($existingQuestionIds, $updatedQuestionIds);
            if (!empty($questionsToDelete)) {
                Question::whereIn('id', $questionsToDelete)->delete();
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Questionnaire updated successfully!',
                'data' => [
                    'questionnaire_id' => $questionnaire->id,
                    'section_changed' => $sectionChanged
                ]
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to update questionnaire: ' . $e->getMessage()
            ], 500);
        }
    }

    public function asController(ActionRequest $request, $id)
    {
        return $this->handle($request, $id);
    }
}
