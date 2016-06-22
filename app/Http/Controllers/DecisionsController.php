<?php
/*
 * This code was generated automatically by Nebo15/REST
 */

namespace App\Http\Controllers;

use Nebo15\REST\AbstractController;

/**
 * Class DecisionsController
 * @package App\Http\Controllers
 * @method \App\Repositories\DecisionsRepository getRepository()
 */
class DecisionsController extends AbstractController
{
    protected $repositoryClassName = 'App\Repositories\DecisionsRepository';

    protected $validationRules = [
        'create' => [],
        'update' => [],
        'updateMeta' => [
            'meta' => 'required|array',
        ],
    ];

    public function readList()
    {
        return $this->response->jsonPaginator(
            $this->getRepository()->getDecisions(
                $this->request->input('size'),
                $this->request->input('table_id'),
                $this->request->input('variant_id')
            )
        );
    }

    public function updateMeta($id)
    {
        $this->validateRoute();

        return $this->response->json($this->getRepository()->updateMeta($id, $this->request->input('meta')));
    }
}
