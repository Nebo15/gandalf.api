<?php
/*
 * This code was generated automatically by Nebo15/REST
 */

namespace App\Http\Controllers;

use Nebo15\REST\Response;
use Illuminate\Http\Request;
use Nebo15\REST\AbstractController;
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
            'title' => 'sometimes|min:1',
            'description' => 'sometimes|min:1',
            'matching_type' => 'sometimes|in:decision,scoring',
        ]
    ];

    public function __construct(Request $request, Response $response)
    {
        $rules = $this->getRepository()->getModel()->getValidationRules();

        $this->validationRules['create'] = $rules;
        $this->validationRules['update'] = $rules;

        parent::__construct($request, $response);
    }

    public function readList()
    {
        $this->validateRoute();

        return $this->response->jsonPaginator(
            $this->getRepository()->readListWithFilters($this->request->all()),
            [],
            function (ListableInterface $model) {
                return $model->toListArray();
            }
        );
    }

    public function analytics($id, $variant_id)
    {
        $this->validateRoute();

        return $this->response->json(
            $this->getRepository()->analyzeTableDecisions($id, $variant_id)->toArray()
        );
    }
}
