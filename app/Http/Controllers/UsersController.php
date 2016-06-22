<?php
/*
 * This code was generated automatically by Nebo15/REST
 */

namespace App\Http\Controllers;

use MongoDB\BSON\Regex;
use App\Models\Invitation;
use Nebo15\REST\Response;
use Nebo15\REST\AbstractController;
use Nebo15\REST\Interfaces\ListableInterface;
use Nebo15\LumenApplicationable\Models\Application;

/**
 * Class UsersController
 * @package App\Http\Controllers
 * @method \App\Repositories\UsersRepository getRepository()
 */
class UsersController extends AbstractController
{
    protected $repositoryClassName = 'App\Repositories\UsersRepository';

    protected $validationRules = [
        'create' => [
            'username' => 'required|unique:users,username|between:2,32|username',
            'first_name' => 'sometimes|required|string|between:2,32|alpha',
            'last_name' => 'sometimes|required|string|between:2,32|alpha',
            'email' => 'required|unique:users,email|email',
            'password' => 'required|between:6,32|password',
        ],
        'update' => [
            'username' => 'required|unique:users,username|between:2,32|username',
            'first_name' => 'sometimes|required|string|between:2,32|alpha',
            'last_name' => 'sometimes|required|string|between:2,32|alpha',
            'email' => 'sometimes|required|unique:users,email|email',
            'password' => 'sometimes|required|between:6,32|password',
        ],
        'createResetPasswordToken' => [
            'email' => 'required|email',
        ],
        'changePassword' => [
            'token' => 'required',
            'password' => 'required|between:6,32|password',
        ],
        'invite' => [
            'email' => 'required|email',
            'role' => 'required|string',
            'scope' => 'required|array',
        ],
    ];

    public function readListWithFilters()
    {
        $query = $this->getRepository()->getModel()->query();
        if (strpos($this->request->input('name', ''), '@') === false) {
            $query->where(['username' => new Regex('^' . ($this->request->input('name', '.')) . '.*', 'i')]);
            $query->where(['username' => ['$ne' => $this->request->user()->username]]);
        } else {
            $query->where(['email' => new Regex('^' . ($this->request->input('name', '.')) . '.*', 'i')]);
            $query->where(['email' => ['$ne' => $this->request->user()->email]]);
        }

        return $this->response->jsonPaginator(
            $this->getRepository()->paginateQuery($query, $this->request->input('size')),
            [],
            function (ListableInterface $model) {
                return $model->toListArray();
            }
        );
    }

    public function verifyEmail()
    {
        return $this->response->json(
            $this->getRepository()->getModel()
                ->findByVerifyEmailToken($this->request->input('token'))
                ->verifyEmail()->save()
                ->toArray()
        );
    }

    public function resendVerifyEmailToken()
    {
        $user = $this->getRepository()->getModel();
        $user->createVerifyEmailToken()->save();
        $this->getMailService()->sendEmailConfirmation(
            $user->temporary_email,
            $user->getVerifyEmailToken()['token'],
            $user->username
        );
        if (env('APP_ENV') == 'local') {
            $sandboxData['token_email'] = $user->getVerifyEmailToken();
        }

        return $this->response->json();
    }


    public function create()
    {
        $this->validateRoute();

        $user = $this->getRepository()->createOrUpdate($this->request->all());
        $sandboxData = [];

        if (env('APP_ENV') == 'local') {
            $sandboxData['token_email'] = $user->getVerifyEmailToken();
        }

        $invitation = Invitation::where('email', $user->email)->get();
        foreach ($invitation as $item) {
            if (array_key_exists('_id', $item->project)) {
                $application = Application::find($item->project['_id']);
                if (!$application->getUser($user->email)) {
                    $application->setUser([
                        'user_id' => (string)$user->_id,
                        'role' => $item->role,
                        'scope' => $item->scope
                    ])->save();
                }
            }
        }

        return $this->response->json(
            $user->toArray(),
            Response::HTTP_CREATED,
            [],
            [],
            $sandboxData
        );
    }

    public function updateUser()
    {
        $this->validateRoute();
        $model = $this->getRepository()->createOrUpdate(
            $this->request->request->all(),
            $this->request->user()->getId()
        );
        $sandboxData = [];
        if (env('APP_ENV') == 'local') {
            $sandboxData['token_email'] = $model->getVerifyEmailToken();
        }

        return $this->response->json(
            $model->toArray(),
            Response::HTTP_OK,
            [],
            [],
            $sandboxData
        );
    }

    public function changePassword()
    {
        $this->validateRoute();

        return $this->response->json(
            $this->getRepository()->getModel()->findByResetPasswordToken(
                $this->request->input('token')
            )->changePassword(
                $this->request->input('password')
            )->save()->toArray()
        );
    }

    public function createResetPasswordToken()
    {
        $this->validateRoute();
        $email = $this->request->input('email');
        /** @var \App\Models\User $user */
        $user = $this->getRepository()->getModel()->query()->where(['email' => $email])->firstOrFail();

        $return = [];
        $sandboxData = [];
        $user->createResetPasswordToken();

        $this->getMailService()->sendRecoveryPassword($email, $user->getResetPasswordToken()['token'], $user);
        $user->save();

        if (env('APP_ENV') == 'local') {
            $sandboxData['reset_password_token'] = $user->getResetPasswordToken();
        }

        return $this->response->json(
            $return,
            Response::HTTP_OK,
            [],
            [],
            $sandboxData
        );
    }

    public function getUserInfo()
    {
        $resp = $this->request->user()->toArray();
        $resp['scope'] = $this->request->user()->getApplicationUser()->scope;
        return $this->response->json($resp);
    }

    public function invite(Application $application)
    {
        $current_user = $this->request->user()->getApplicationUser();
        $this->validationRules['invite']['scope'] = 'required|array|in:' . join(',', $current_user->scope);
        $this->validateRoute();
        $project = $application->toArray();
        $fill = $this->request->all();
        $fill['project'] = [
            '_id' => $project['_id'],
            'title' => $project['title'],
        ];

        return $this->response->json((new Invitation())->fill($fill)->save()->toArray());
    }

    /**
     * @return \App\Services\Mail
     */
    private function getMailService()
    {
        return app('\App\Services\Mail');
    }
}
