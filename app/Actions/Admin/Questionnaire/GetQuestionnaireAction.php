<?php

namespace App\Actions\Admin\Questionnaire;

use App\Actions\BaseAction;
use App\Models\Questionnaire;
use App\Models\QuestionnaireResponse;
use Lorisleiva\Actions\Concerns\AsAction;
use Illuminate\Http\Request;

class GetQuestionnaireAction extends BaseAction
{
    use AsAction;


    protected string $title = 'Questionnaire';
    protected string $view = 'admin.questionnaire';
    protected string $url = 'questionnaires';
    protected string $permission = 'questionnaire';

    public function handle(?int $id = null)
    {
        return  $id ? Questionnaire::findOrFail($id) : new Questionnaire();
    }

    public function asController(Request $request, $id = null)
    {
        $routeName = $request->route()->getName(); // Get the route name
        $record = $this->handle($id);
        $select_id = $request->select_id;
        $analytics = [];
        foreach ($record->questions as $question) {
            $answers = QuestionnaireResponse::where('question_id', $question->id)->get();

            $analytics[$question->id] = [
                'question' => $question->question,
                'type' => $question->type,
                'total_responses' => $answers->count(),
                'answers' => $answers
            ];

            // Calculate statistics based on question type
            if ($question->type === 'rating' || $question->type === 'scale') {
                $values = $answers->pluck('answer')->filter()->map(fn ($v) => (float)$v);
                $analytics[$question->id]['average'] = $values->avg();
                $analytics[$question->id]['min'] = $values->min();
                $analytics[$question->id]['max'] = $values->max();
            }

            if (in_array($question->type, ['radio', 'checkbox', 'select'])) {
                $analytics[$question->id]['distribution'] = $answers
                    ->groupBy('answer')
                    ->map(fn ($group) => $group->count())
                    ->toArray();
            }
        }
        return match ($routeName) {
            $this->view . '.create' => view($this->view . '.create', get_defined_vars()),
            $this->view . '.edit' => view($this->view . '.edit', get_defined_vars()),
            $this->view . '.show' => view($this->view . '.show', get_defined_vars()),
        };
    }
}
