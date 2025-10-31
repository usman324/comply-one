<?php

namespace App\Actions\Admin\Workspace\User;

use App\Actions\BaseAction;
use App\Models\Question;
use App\Models\Questionnaire;
use App\Models\Workspace;
use App\Models\QuestionnaireResponse;
use App\Models\Section;
use App\Models\User;
use Lorisleiva\Actions\ActionRequest;
use Illuminate\Contracts\View\View;
use Lorisleiva\Actions\Concerns\AsAction;
use Spatie\Permission\Models\Role;

class GetWorkspaceUserAction extends BaseAction
{
    use AsAction;

    protected string $title = 'Workspace';
    protected string $view = 'admin.workspace.user';
    protected string $url = 'workspaces';
    protected string $permission = 'workspace';

    public function asController(ActionRequest $request, ?Workspace $workspace, User $user): View
    {
        $routeName = $request->route()->getName();
        $record = $user;
        $roles = Role::all();

        if ($request->assign) {
            // Get all available questions
            $availableQuestions = Question::with(['questionnaire', 'workspaces'])
                ->ordered()
                ->get();

            // Get all questionnaires for filtering
            $questionnaires = Questionnaire::orderBy('title')->get();

            return view("{$this->view}.assign-question", compact(
                'record',
                'availableQuestions',
                'questionnaires'
            ));
        }

        // Get all active questionnaires grouped by section
        $questionnaires = Questionnaire::where('status', 'active')
            ->with(['questions', 'section'])
            ->orderBy('section_id')
            ->get();

        // Group questionnaires by section and process questions
        $questionnaireSections = $questionnaires->groupBy('section_id')->map(function ($sectionQuestionnaires, $q_section) {
            $questions = $sectionQuestionnaires->first()->questions ?? collect([]);
            $section = Section::find($q_section)?->name;

            // Process each question to ensure options are arrays
            $questions = $questions->map(function ($question) {
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
                'questions' => $questions,
            ];
        })->values();

        // Get existing responses if editing
        $existingResponses = [];
        if ($record) {
            $responses = QuestionnaireResponse::whereBelongsTo($record)->get();

            foreach ($responses as $response) {
                $answer = $response->answer;
                $decoded = json_decode($answer, true);
                $existingResponses[$response->question_id] = $decoded ?? $answer;
            }
        }

        return match ($routeName) {
            "{$this->view}.create" => view("{$this->view}.create", compact(
                'record',
                'questionnaires',
                'questionnaireSections',
                'existingResponses',
                'roles'
            )),
            "{$this->view}.edit" => view("{$this->view}.edit", compact(
                'record',
                'questionnaires',
                'questionnaireSections',
                'existingResponses',
                'roles'
            )),
            "{$this->view}.show" => view("{$this->view}.show", compact(
                'record',
                'questionnaires',
                'questionnaireSections',
                'existingResponses',
                'roles'
            )),
        };
    }
}
