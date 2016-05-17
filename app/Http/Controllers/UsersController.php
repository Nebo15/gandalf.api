<?php
/*
 * This code was generated automatically by Nebo15/REST
 */

namespace App\Http\Controllers;

use Nebo15\REST\AbstractController;
use Nebo15\REST\Interfaces\ListableInterface;

class UsersController extends AbstractController
{
    protected $repositoryClassName = 'App\Repositories\UsersRepository';

    protected $validationRules = [
        'create' => [
            'username' => 'required|unique:users,username|min:2|max:32',
            'email' => 'required|unique:users,email|email',
            'password' => 'required',
        ],
        'update' => [
            'username' => 'sometimes|required|unique:users,username|min:2|max:32',
            'email' => 'sometimes|required|unique:users,email|email',
            'password' => 'sometimes|required',
        ],
    ];

    public function readListWithFilters()
    {
        $model = $this->getRepository()
            ->getModel()
            ->query();
        if (strstr($this->request->input('name', ''), '@') === false) {
            $model->where(['username' => new \MongoRegex('/^' . ($this->request->input('name', '.')) . '.*/\i')]);
        } else {
            $model->where(['email' => new \MongoRegex('/^' . ($this->request->input('name', '.')) . '.*/\i')]);
        }

        return $this->response->jsonPaginator(
            $model
                ->paginate(intval($this->request->input('size'))),
            [],
            function (ListableInterface $model) {
                return $model->toListArray();
            }
        );
    }

    public function getUserInfo()
    {
        return $this->response->json($this->request->user()->toArray());
    }

}
