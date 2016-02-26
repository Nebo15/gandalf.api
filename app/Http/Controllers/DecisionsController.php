<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DecisionTable;
use App\Http\Services\Response;
use App\Services\ConditionsTypes;
use App\Repositories\DecisionRepository;
use App\Http\Traits\ValidatesRequestsCatcher;

class DecisionsController extends Controller
{
    use ValidatesRequestsCatcher;

    private $decisionRepository;
    private $response;

    private $tableValidationRules;

    public function __construct(Response $response, DecisionRepository $decision, ConditionsTypes $conditionsTypes)
    {
        $this->decisionRepository = $decision;
        $this->response = $response;
        $this->tableValidationRules = [
            'table' => 'required|array',
            'table.title' => 'sometimes|string',
            'table.description' => 'sometimes|string',
            'table.default_decision' => 'required|string',
            'table.fields' => 'required|array',
            'table.fields.*.title' => 'required|string',
            'table.fields.*.key' => 'required|string',
            'table.fields.*.type' => 'required|in:numeric,boolean,string',
            'table.fields.*.source' => 'required|in:request',
            'table.rules' => 'required|array',
            'table.rules.*.than' => 'required|string',
            'table.rules.*.description' => 'sometimes|string',
            'table.rules.*.conditions' => 'required|array|conditionsCount',
//            'table.rules.*.conditions.*.field_key' => 'required|string|conditionsField',
//            'table.rules.*.conditions.*.condition' => 'required|in:' . $conditionsTypes->getConditionsRules(),
//            'table.rules.*.conditions.*.value' => 'required|conditionType',
        ];
    }

    public function index(Request $request)
    {
        return $this->response->jsonPaginator(
            $this->decisionRepository->all($request->input('size')),
            [],
            function (DecisionTable $decisionTable) {
                return $decisionTable->toListArray();
            }
        );
    }

    public function get($id)
    {
        return $this->response->json($this->decisionRepository->get($id)->toArray());
    }

    public function create(Request $request)
    {
        $this->validate($request, $this->tableValidationRules);

        return $this->response->json(
            $this->decisionRepository->create($request->input('table'))->toArray(),
            Response::HTTP_CREATED
        );
    }

    public function cloneModel($id)
    {
        return $this->response->json(
            $this->decisionRepository->cloneModel($id)->toArray()
        );
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, $this->tableValidationRules);

        return $this->response->json(
            $this->decisionRepository->update($id, $request->input('table'))->toArray()
        );
    }

    public function delete($id)
    {
        return $this->response->json(
            $this->decisionRepository->delete($id)
        );
    }

    public function history(Request $request)
    {
        return $this->response->jsonPaginator(
            $this->decisionRepository->history($request->input('size'), $request->input('table_id'))
        );
    }

    public function historyItem($id)
    {
        return $this->response->json($this->decisionRepository->historyItem($id)->toArray());
    }
}
