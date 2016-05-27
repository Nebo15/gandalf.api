<?php
namespace App\Http\Controllers;

use Laravel\Lumen\Routing\Controller;
use Nebo15\Changelog\Changelog;
use Nebo15\Changelog\ControllerInterface;
use Nebo15\LumenApplicationable\ApplicationableHelper;
use Nebo15\REST\Response;
use Illuminate\Http\Request;
use Illuminate\Contracts\Validation\ValidationException;

class ChangelogController extends Controller implements ControllerInterface
{
    protected $request;

    protected $response;

    protected $changelogModel;

    public function __construct(Request $request, Response $response, Changelog $changelogModel)
    {
        $this->request = $request;
        $this->response = $response;
        $this->changelogModel = $changelogModel;
    }

    public function all($table)
    {
        return $this->response->jsonPaginator(
            $this->changelogModel->findAll(
                $table,
                null,
                $this->request->get('size'),
                ['model.attributes.applications' => ApplicationableHelper::getApplicationId()]
            )
        );
    }

    public function allWithId($table, $model_id)
    {
        return $this->response->jsonPaginator(
            $this->changelogModel->findAll(
                $table,
                $model_id,
                $this->request->get('size'),
                ['model.attributes.applications' => ApplicationableHelper::getApplicationId()]
            )
        );
    }

    public function diff($table, $model_id)
    {
        $this->validate($this->request, [
            'compare_with' => 'required',
            'original' => 'sometimes|required',
        ]);

        return $this->response->json(
            $this->changelogModel->diff(
                $table,
                $model_id,
                $this->request->input('compare_with'),
                $this->request->input('original'),
                ['model.attributes.applications' => ApplicationableHelper::getApplicationId()]
            )
        );
    }

    public function rollback($table, $model_id, $changelog_id)
    {
        return $this->response->json(
            $this->changelogModel->rollback(
                $table,
                $model_id,
                $changelog_id,
                ['model.attributes.applications' => ApplicationableHelper::getApplicationId()]
            )
        );
    }

    protected function throwValidationException(Request $request, $validator)
    {
        throw new ValidationException($validator);
    }
}
