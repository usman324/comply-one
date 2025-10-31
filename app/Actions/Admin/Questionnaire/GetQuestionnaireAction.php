<?php

namespace App\Actions\Admin\Questionnaire;

use App\Actions\BaseAction;
use App\Models\Questionnaire;
use App\Models\QuestionnaireResponse;
use Illuminate\Http\Request;
use Lorisleiva\Actions\Concerns\AsAction;

class GetQuestionnaireAction extends BaseAction
{
    use AsAction;

    protected string $title = 'Questionnaire';
    protected string $view = 'admin.questionnaire';
    protected string $url = 'questionnaires';
    protected string $permission = 'questionnaire';

    public function handle(?int $id = null): Questionnaire
    {
        return $id ? Questionnaire::findOrFail($id) : new Questionnaire();
    }

    public function asController(Request $request, $id = null)
    {
        $record = $this->handle($id);
        $select_id = $request->select_id;
        $routeName = $request->route()->getName();

        $analytics = $this->generateAnalytics($record);

        $viewName = match ($routeName) {
            "{$this->view}.create" => "{$this->view}.create",
            "{$this->view}.edit"   => "{$this->view}.edit",
            default                => "{$this->view}.show",
        };

        return view($viewName, compact('record', 'analytics', 'select_id'));
    }

    protected function generateAnalytics(Questionnaire $record): array
    {
        $analytics = [];
        foreach ($record->questions as $question) {
            $answers = QuestionnaireResponse::where('question_id', $question->id)->get();

            $analytics[$question->id] = [
                'question' => $question->question,
                'type' => $question->type,
                'total_responses' => $answers->count(),
                'answers' => $answers,
            ];

            if (in_array($question->type, ['rating', 'scale'])) {
                $values = $answers->pluck('answer')->filter()->map(fn ($v) => (float) $v);
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
        return $analytics;
    }
}
