<?php

namespace App\Actions\Admin\Workspace;

use App\Actions\BaseAction;
use App\Models\Questionnaire;
use App\Models\Workspace;
use App\Models\QuestionnaireResponse;
use Lorisleiva\Actions\Concerns\AsAction;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class GetWorkspaceAction extends BaseAction
{
    use AsAction;

    protected string $title = 'Workspace';
    protected string $view = 'admin.workspace';
    protected string $url = 'workspaces';
    protected string $permission = 'workspace';


    public function handle(?int $id = null)
    {
        return $id ? Workspace::findOrFail($id) : new Workspace();
    }

    public function asController(Request $request, $id = null)
    {
        $routeName = $request->route()->getName();
        $record = $this->handle($id);
        $roles = Role::where('is_hidden', false)->get();

        // Get all active questionnaires grouped by section
        $questionnaires = Questionnaire::where('status', 'active')
            ->where('start_date', '<=', now())
            ->where('end_date', '>=', now())
            ->with('questions')
            ->orderBy('section')
            ->get();

        // Group questionnaires by section and process questions
        $questionnaireSections = $questionnaires->groupBy('section')->map(function ($sectionQuestionnaires, $section) {
            // Get questions and ensure options are properly formatted
            $questions = $sectionQuestionnaires->first()->questions ?? collect([]);

            // Process each question to ensure options are arrays
            $questions = $questions->map(function ($question) {
                // If options exists and is a string, decode it
                if (isset($question->options)) {
                    if (is_string($question->options)) {
                        $decoded = json_decode($question->options, true);
                        $question->options = $decoded ?? [];
                    } elseif (!is_array($question->options)) {
                        $question->options = [];
                    }
                } else {
                    $question->options = [];
                }
                return $question;
            });

            return [
                'name' => ucfirst(str_replace('_', ' ', $section)),
                'slug' => $section,
                'description' => $sectionQuestionnaires->first()->description ?? null,
                'questions' => $questions
            ];
        })->values();

        // Get existing responses if editing
        $existingResponses = [];
        if ($id) {
            $responses = QuestionnaireResponse::where('workspace_id', $id)->get();
            foreach ($responses as $response) {
                // Try to decode JSON answers (for checkbox/multi-select)
                $answer = $response->answer;
                $decoded = json_decode($answer, true);
                $existingResponses[$response->question_id] = $decoded ?? $answer;
            }
        }

        return match ($routeName) {
            $this->view . '.create' => view($this->view . '.create', get_defined_vars()),
            $this->view . '.edit' => view($this->view . '.edit', get_defined_vars()),
            $this->view . '.show' => view($this->view . '.show', get_defined_vars()),
        };
    }
}
