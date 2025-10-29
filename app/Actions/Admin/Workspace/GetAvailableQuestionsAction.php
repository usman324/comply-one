<?php

namespace App\Actions\Admin\Workspace;

use App\Actions\BaseAction;
use App\Models\Workspace;
use App\Models\Question;

class GetAvailableQuestionsAction extends BaseAction
{
    protected string $title = 'Workspace';
    protected string $view = 'admin.workspace';
    protected string $url = 'workspaces';
    protected string $permission = 'workspace';

    /**
     * Get available questions not assigned to workspace
     */
    public function handle(int $workspaceId)
    {
        $workspace = Workspace::findOrFail($workspaceId);

        $assignedQuestionIds = $workspace->questions()->pluck('questions.id');

        $questions = Question::with('questionnaire')
            ->whereNotIn('id', $assignedQuestionIds)
            ->ordered()
            ->get();

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
