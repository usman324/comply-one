<?php

namespace App\Actions\Admin\Workspace;

use App\Actions\BaseAction;
use App\Models\Workspace;

class GetWorkspaceQuestionsAction extends BaseAction
{
    protected string $title = 'Workspace';
    protected string $view = 'admin.workspace';
    protected string $url = 'workspaces';
    protected string $permission = 'workspace';

    /**
     * Get questions for a workspace
     */
    public function handle(int $workspaceId)
    {
        $workspace = Workspace::findOrFail($workspaceId);

        $questions = $workspace->questions()
            ->with('questionnaire')
            ->get()
            ->map(function ($question) {
                return [
                    'id' => $question->id,
                    'question' => $question->question,
                    'type' => $question->type,
                    'questionnaire' => $question->questionnaire?->name,
                    'order' => $question->pivot->order,
                    'is_required' => $question->pivot->is_required || $question->is_required,
                    'workspace_specific_options' => json_decode($question->pivot->workspace_specific_options, true),
                ];
            });

        return [
            'success' => true,
            'data' => $questions,
        ];
    }

    public function asController(Workspace $workspace)
    {
        $result = $this->handle($workspace->id);

        return response()->json($result);
    }
}
