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

class StoreQuestionnaireAction extends BaseAction
{
    use AsAction;
    use RespondsWithJson;
    use CustomAction;

    protected string $title = 'Questionnaire';
    protected string $view = 'admin.questionnaire';
    protected string $url = 'questionnaires';
    protected string $permission = 'questionnaire';


    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'section_id' => 'required', // Validate section exists
            'description' => 'nullable|string',
            'questions' => 'required|json'
        ];
    }
    public function handle(
        $request,
    ) {
        DB::beginTransaction();
        try {
            // Check if questionnaire for this section already exists
            $questionnaire = Questionnaire::where('section_id', $request->section_id)
                ->where('status', 'active')
                ->first();

            if ($questionnaire) {
                // Section questionnaire exists - just add questions to it
                $questions = json_decode($request->questions, true);

                // Get the last order number for this questionnaire
                $lastOrder = Question::where('questionnaire_id', $questionnaire->id)
                    ->max('order') ?? 0;

                foreach ($questions as $index => $questionData) {
                    Question::create([
                        'questionnaire_id' => $questionnaire->id,
                        'question' => $questionData['question'],
                        'type' => $questionData['type'],
                        'description' => $questionData['description'] ?? null,
                        'options' => isset($questionData['options']) ? json_encode($questionData['options']) : null,
                        'is_required' => $questionData['is_required'] ?? false,
                        'order' => $lastOrder + $index + 1
                    ]);
                }

                DB::commit();

                return response()->json([
                    'success' => true,
                    'message' => count($questions) . ' question(s) added to existing section questionnaire!',
                    'data' => [
                        'questionnaire_id' => $questionnaire->id,
                        'is_new' => false,
                        'questions_added' => count($questions)
                    ]
                ]);
            } else {
                // No questionnaire exists for this section - create new one
                $questionnaire = Questionnaire::create([
                    'user_id' => getUser()->id,
                    'title' => $request->title,
                    'section_id' => $request->section_id,
                    'description' => $request->description,
                    'allow_anonymous' => $request->has('allow_anonymous'),
                    'allow_multiple_responses' => $request->has('allow_multiple_responses'),
                    'show_progress' => $request->has('show_progress'),
                    'randomize_questions' => $request->has('randomize_questions'),
                    'status' => 'active',
                ]);

                // Create questions
                $questions = json_decode($request->questions, true);

                foreach ($questions as $index => $questionData) {
                    Question::create([
                        'questionnaire_id' => $questionnaire->id,
                        'question' => $questionData['question'],
                        'type' => $questionData['type'],
                        'description' => $questionData['description'] ?? null,
                        'options' => isset($questionData['options']) ? json_encode($questionData['options']) : null,
                        'is_required' => $questionData['is_required'] ?? false,
                        'order' => $index + 1
                    ]);
                }

                DB::commit();

                return response()->json([
                    'success' => true,
                    'message' => 'Questionnaire created successfully with ' . count($questions) . ' question(s)!',
                    'data' => [
                        'questionnaire_id' => $questionnaire->id,
                        'is_new' => true,
                        'questions_added' => count($questions)
                    ]
                ]);
            }
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to process questionnaire: ' . $e->getMessage()
            ], 500);
        }
    }

    public function asController(ActionRequest $request)
    {
        return $this->handle($request);
    }
}
