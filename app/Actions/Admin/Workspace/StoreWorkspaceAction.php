<?php

namespace App\Actions\Admin\Workspace;

use App\Actions\BaseAction;
use App\Models\Workspace;
use App\Models\QuestionnaireResponse;
use App\Models\Question;
use App\Traits\CustomAction;
use Lorisleiva\Actions\ActionRequest;
use App\Traits\RespondsWithJson;
use Exception;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsAction;

class StoreWorkspaceAction extends BaseAction
{
    use AsAction;
    use RespondsWithJson;
    use CustomAction;

    protected string $title = 'Workspace';
    protected string $view = 'admin.workspace';
    protected string $url = 'workspaces';
    protected string $permission = 'workspace';

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'nullable|string|in:personal,team,enterprise',
            'status' => 'nullable|string|in:active,inactive,pending',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'answers' => 'nullable|array',
            'answers.*' => 'nullable',
        ];
    }

    public function handle($request)
    {
        try {
            DB::beginTransaction();

            // Handle avatar upload
            $avatar_name = '';
            if ($request->hasFile('avatar')) {
                $image = $request->file('avatar');
                $name = rand(10, 100) . time() . '.' . $image->getClientOriginalExtension();
                $image->storeAs('workspace', $name);
                $avatar_name = $name;
            }

            // Create workspace
            $workspace = Workspace::create([
                'name' => $request->name,
                'description' => $request->description,
                'type' => $request->type ?? 'personal',
                'status' => $request->status ?? 'active',
                'avatar' => $avatar_name,
                'workspace_number' => Workspace::generateWorkspaceNumber(),
                'owner_id' => getUser()->id,
                'created_by' => getUser()->id,
            ]);

            // Save questionnaire responses if provided
            if ($request->has('answers') && is_array($request->answers)) {
                foreach ($request->answers as $question_id => $answer) {
                    if ($answer !== null && $answer !== '') {
                        // Get the question to find questionnaire_id
                        $question = Question::find($question_id);

                        if ($question) {
                            // Handle array answers (checkbox, multi-select)
                            $answerValue = is_array($answer) ? json_encode($answer) : $answer;
                            $answerText = is_array($answer) ? implode(', ', $answer) : $answer;

                            $questionnaire = $question->questionnaire;
                            QuestionnaireResponse::create([
                                'workspace_id' => $workspace->id,
                                'user_id' => getUser()->id,
                                'section' => $questionnaire->section,
                                'questionnaire_id' => $questionnaire->id,
                                'question_id' => $question_id,
                                'answer' => $answerValue,
                                'answer_text' => $answerText,
                                'answered_at' => now(),
                            ]);
                        }
                    }
                }
            }

            DB::commit();

            return $this->success('Workspace created successfully', [
                'workspace' => [
                    'id' => $workspace->id,
                    'name' => $workspace->name,
                    'workspace_number' => $workspace->workspace_number,
                    'type' => $workspace->type,
                    'status' => $workspace->status,
                ]
            ]);
        } catch (Exception $e) {
            DB::rollBack();
            report($e);
            return $this->error($e->getMessage());
        }
    }

    public function asController(ActionRequest $request)
    {
        return $this->handle($request);
    }
}
