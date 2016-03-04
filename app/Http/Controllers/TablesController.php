<?php
/*
 * This code was generated automatically by Nebo15/REST
 */

namespace App\Http\Controllers;

use Nebo15\REST\AbstractController;

use Nebo15\REST\Response;
use Illuminate\Http\Request;
use App\Services\ConditionsTypes;

class TablesController extends AbstractController
{
    protected $repositoryClassName = 'App\Repositories\TablesRepository';

    protected $validationRules = [
        'create' => [],
        'update' => [],
    ];

    public function __construct(Request $request, Response $response, ConditionsTypes $conditionsTypes)
    {
        $rules = [
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
            'table.rules.*.conditions.*.field_key' => 'required|string|conditionsField',
            'table.rules.*.conditions.*.condition' => 'required|in:' . $conditionsTypes->getConditionsRules(),
            'table.rules.*.conditions.*.value' => 'required|conditionType',
        ];
        $this->validationRules['create'] = $rules;
        $this->validationRules['update'] = $rules;

        parent::__construct($request, $response);
    }

    public function create()
    {
        $this->validateRoute();

        return $this->response->json(
            $this->getRepository()->createOrUpdate($this->request->input('table'))->toArray(),
            Response::HTTP_CREATED
        );
    }

    public function update($id)
    {
        $this->validateRoute();

        return $this->response->json(
            $this->getRepository()->createOrUpdate($this->request->input('table'), $id)->toArray()
        );
    }

    public function history()
    {
        return $this->response->jsonPaginator(
            $this->getRepository()->history($this->request->input('size'), $this->request->input('table_id'))
        );
    }

    public function historyItem($id)
    {
        return $this->response->json($this->getRepository()->historyItem($id)->toArray());
    }
}