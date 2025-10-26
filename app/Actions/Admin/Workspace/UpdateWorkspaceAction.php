<?php

namespace App\Actions\Admin\Workspace;

use App\Actions\BaseAction;
use App\Models\Workspace;
use App\Models\QuestionnaireResponse;
use App\Models\QuestionnaireQuestion;
use App\Traits\CustomAction;
use Lorisleiva\Actions\ActionRequest;
use App\Traits\RespondsWithJson;
use Exception;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsAction;

class UpdateWorkspaceAction extends BaseAction
{
    use AsAction;
    use RespondsWithJson;
    use CustomAction;

    protected string $title = 'Workspace';
    protected string $view = 'admin.workspace';
    protected string $url = 'workspaces';
    protected string $permission = 'workspace';

    public function rules(ActionRequest $request): array
    {
        return [
            'workspace_name' => 'required|string|max:255',
            'workspace_description' => 'nullable|string',
            'workspace_type' => 'nullable|string|in:personal,team,enterprise',
            'workspace_status' => 'nullable|string|in:active,inactive,pending',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'answers' => 'nullable|array',
            'answers.*' => 'nullable',
        ];
    }

    public function handle($request, int $id)
    {
        try {
            DB::beginTransaction();

            $workspace = Workspace::findOrFail($id);

            // Handle avatar upload
            $avatar_name = $workspace->avatar;
            if ($request->hasFile('avatar')) {
                // Delete old avatar
                if ($workspace->avatar) {
                    deleteImage('workspace/' . $workspace->avatar, $workspace->avatar);
                }

                $image = $request->file('avatar');
                $name = rand(10, 100) . time() . '.' . $image->getClientOriginalExtension();
                $image->storeAs('workspace', $name);
                $avatar_name = $name;
            }

            // Update workspace details
            $workspace->update([
                'name' => $request->workspace_name,
                'description' => $request->workspace_description ?? $workspace->description,
                'type' => $request->workspace_type ?? $workspace->type,
                'status' => $request->workspace_status ?? $workspace->status,
                'avatar' => $avatar_name,
                'updated_by' => auth()->id(),
            ]);

            // Update questionnaire responses if provided
            if ($request->has('answers') && is_array($request->answers)) {
                foreach ($request->answers as $question_id => $answer) {
                    if ($answer !== null && $answer !== '') {
                        // Get the question to find questionnaire_id and section
                        $question = QuestionnaireQuestion::with('questionnaire')->find($question_id);

                        if ($question) {
                            // Handle array answers (checkbox, multi-select)
                            $answerValue = is_array($answer) ? json_encode($answer) : $answer;
                            $answerText = is_array($answer) ? implode(', ', $answer) : $answer;

                            // Use updateOrCreate to avoid duplicate entry errors
                            QuestionnaireResponse::updateOrCreate(
                                [
                                    'workspace_id' => $workspace->id,
                                    'user_id' => auth()->id(),
                                    'question_id' => $question_id,
                                ],
                                [
                                    'section' => $question->questionnaire->section ?? null,
                                    'questionnaire_id' => $question->questionnaire_id,
                                    'answer' => $answerValue,
                                    'answer_text' => $answerText,
                                    'answered_at' => now(),
                                ]
                            );
                        }
                    }
                }
            }

            DB::commit();

            return $this->success('Workspace updated successfully', [
                'workspace' => [
                    'id' => $workspace->id,
                    'workspace_number' => $workspace->workspace_number,
                    'type' => $workspace->type,
                    'status' => $workspace->status,
                ]
            ]);

        } catch (Exception $e) {
            DB::rollBack();
            return $this->error($e->getMessage());
        }
    }

    public function asController(ActionRequest $request, $id)
    {
        return $this->handle($request, $id);
    }
}
