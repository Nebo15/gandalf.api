<?php
/*
 * This code was generated automatically by Nebo15/REST
 */

namespace App\Http\Controllers;

use Nebo15\REST\AbstractController;
use Nebo15\REST\Interfaces\ListableInterface;
use Nebo15\REST\Response;

class UsersController extends AbstractController
{
    protected $repositoryClassName = 'App\Repositories\UsersRepository';

    protected $validationRules = [
        'create' => [
            'username' => 'required|unique:users,username|min:2|max:32',
            'first_name' => 'sometimes|required|string|min:2|max:32',
            'last_name' => 'sometimes|required|string|min:2|max:32',
            'email' => 'required|unique:users,email|email',
            'password' => 'required',
        ],
        'update' => [
            'username' => 'sometimes|required|unique:users,username|min:2|max:32',
            'email' => 'sometimes|required|unique:users,email|email',
            'first_name' => 'sometimes|required|string|min:2|max:32',
            'last_name' => 'sometimes|required|string|min:2|max:32',
            'password' => 'sometimes|required',
        ],
    ];

    public function readListWithFilters()
    {
        $model = $this->getRepository()
            ->getModel()
            ->query();
        if (strpos($this->request->input('name', ''), '@') === false) {
            $model->where(['username' => new \MongoRegex('/^' . ($this->request->input('name', '.')) . '.*/\i')]);
        } else {
            $model->where(['email' => new \MongoRegex('/^' . ($this->request->input('name', '.')) . '.*/\i')]);
        }

        return $this->response->jsonPaginator(
            $model->paginate(intval($this->request->input('size'))),
            [],
            function (ListableInterface $model) {
                return $model->toListArray();
            }
        );
    }

    public function verifyEmail()
    {
        return $this->response->json(
            $this->getRepository()->getModel()->findByVerifyEmailToken($this->request->input('token'))
                ->verifyEmail()->save()
                ->toArray()
        );
    }


    public function create()
    {
        $this->validateRoute();

        $model = $this->getRepository()->createOrUpdate($this->request->all());
        $sandboxData = [];
        if (env('APP_ENV') == 'local') {
            $sandboxData['token_email'] = $model->getVerifyEmailToken();
        }
        return $this->response->json(
            $model->toArray(),
            Response::HTTP_CREATED,
            [],
            [],
            $sandboxData
        );
    }

    public function updateUser()
    {
        $this->validateRoute();

        return $this->response->json(
            $this->getRepository()->createOrUpdate($this->request->request->all(), $this->request->user()->getId())->toArray()
        );
    }

    public function getUserInfo()
    {
        return $this->response->json($this->request->user()->toArray());
    }
}
