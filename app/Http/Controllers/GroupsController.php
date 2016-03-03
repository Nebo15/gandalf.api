<?php
/**
 * Author: Paul Bardack paul.bardack@gmail.com http://paulbardack.com
 * Date: 03.03.16
 * Time: 16:23
 */

namespace App\Http\Controllers;

class GroupsController extends RESTController
{
    protected $repositoryClassName = 'App\Repositories\GroupsRepository';

    protected $validationRules = [
        'create' => [
            'tables' => 'required|array',
            'tables.*._id' => 'required|string',
            'probability' => 'required|in:random|'
        ],
        'update' => [
            'tables' => 'sometimes|required|array',
            'tables.*._id' => 'sometimes|required|string',
            'probability' => 'sometimes|required'
        ]
    ];
}
