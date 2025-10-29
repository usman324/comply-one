<?php

namespace App\Actions\Admin\Questionnaire;

use App\Actions\BaseAction;
use App\Models\Question;
use App\Models\Questionnaire;
use App\Traits\CustomAction;
use Lorisleiva\Actions\ActionRequest;
use App\Traits\RespondsWithJson;
use Exception;
use Lorisleiva\Actions\Concerns\AsAction;

class DeleteQuestionnaireAction extends BaseAction
{
    use AsAction;
    use RespondsWithJson;
    use CustomAction;


    protected string $title = 'Questionnaire';
    protected string $view = 'admin.questionnaire';
    protected string $url = 'questionnaires';
    protected string $permission = 'questionnaire';

    public function handle(
        int $id
    ) {
        try {
            $record = Questionnaire::findOrFail($id);
            Question::where('question_id', $id)->delete();
            $record->delete();
            return $this->success('Record Deleted Successfully');
        } catch (Exception $e) {
            return  $this->error($e->getMessage());
        }
    }

    public function asController(ActionRequest $request, $id)
    {
        return $this->handle($id);
    }
}
