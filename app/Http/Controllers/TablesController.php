<?php
/*
 * This code was generated automatically by Nebo15/REST
 */

namespace App\Http\Controllers;

use Nebo15\REST\AbstractController;

use Nebo15\REST\Response;
use Illuminate\Http\Request;
use App\Services\ConditionsTypes;
use Nebo15\REST\Interfaces\ListableInterface;

/**
 * Class TablesController
 * @package App\Http\Controllers
 * @method \App\Repositories\TablesRepository getRepository()
 */
class TablesController extends AbstractController
{
    protected $repositoryClassName = 'App\Repositories\TablesRepository';

    protected $validationRules = [
        'create' => [],
        'update' => [],
        'readList' => [
            'title' => 'sometimes|min:2',
            'description' => 'sometimes|min:2',
        ]
    ];

    public function __construct(Request $request, Response $response, ConditionsTypes $conditionsTypes)
    {
        $rules = [
            'table' => 'required|array',
            'table.title' => 'sometimes|string',
            'table.description' => 'sometimes|string',
            'table.default_decision' => 'required|ruleThanType',
            'table.default_title' => 'sometimes|required|between:2,128',
            'table.default_description' => 'sometimes|required|between:2,512',
            'table.matching_type' => 'required|in:first,all',
            'table.fields' => 'required|array',
            'table.fields.*._id' => 'sometimes|mongoId',
            'table.fields.*.title' => 'required|string',
            'table.fields.*.key' => 'required|string|not_in:webhook',
            'table.fields.*.type' => 'required|in:numeric,boolean,string',
            'table.fields.*.source' => 'required|in:request',
            'table.fields.*.preset' => 'present|array',
            'table.fields.*.preset._id' => 'mongoId',
            'table.fields.*.preset.value' => 'required_with:table.fields.*.preset',
            'table.fields.*.preset.condition' => 'required_with:table.fields.*.preset|in:' . $conditionsTypes->getConditionsRules(),
            'table.rules' => 'required|array',
            'table.rules.*._id' => 'mongoId',
            'table.rules.*.than' => 'required|ruleThanType',
            'table.rules.*.description' => 'sometimes|string',
            'table.rules.*.conditions' => 'required|array|conditionsCount',
            'table.rules.*.conditions.*._id' => 'mongoId',
            'table.rules.*.conditions.*.field_key' => 'required|string|conditionsFieldKey',
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

    public function readList()
    {
        return $this->response->jsonPaginator(
            $this->getRepository()->readListWithFilters($this->request->all()),
            [],
            function (ListableInterface $model) {
                return $model->toListArray();
            }
        );
    }

    public function analytics($id)
    {
        return $this->response->json($this->getRepository()->analyzeTableDecisions($id)->toArray());
    }
}
