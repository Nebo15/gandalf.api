<?php
/*
 * This code was generated automatically by Nebo15/REST
 */

namespace App\Http\Controllers;

use Nebo15\REST\AbstractController;

class TreesController extends AbstractController
{
    protected $repositoryClassName = 'App\Repositories\TreesRepository';

    protected $validationRules = [
        'create' => [
            'title' => 'sometimes|string|between:2,128',
            'description' => 'sometimes|string|between:2,512',
            'table_id' => 'required|string',
            'transitions' => 'required|array'
        ],
        'update' => [
            'title' => 'sometimes|string|between:2,128',
            'description' => 'sometimes|string|between:2,512',
            'table_id' => 'sometimes|string',
            'transitions' => 'sometimes|array'
        ],
    ];
}
