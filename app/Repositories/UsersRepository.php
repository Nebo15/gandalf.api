<?php
/*
 * This code was generated automatically by Nebo15/REST
 */

namespace App\Repositories;

use App\Models\User;
use App\Events\Users\Create;
use App\Events\Users\Update;
use Nebo15\REST\AbstractRepository;
use Illuminate\Auth\Access\AuthorizationException;
use Nebo15\LumenApplicationable\ApplicationableHelper;
use Nebo15\LumenApplicationable\Contracts\Applicationable;

/**
 * Class UsersRepository
 * @package App\Repositories
 * @method User getModel()
 */
class UsersRepository extends AbstractRepository
{
    protected $modelClassName = 'App\Models\User';
    protected $observerClassName = 'App\Observers\UserObserver';


    public function createOrUpdate($values, $id = null)
    {
        /** @var User $user */
        $user = $id ? $this->read($id) : $this->getModel()->newInstance();
        if ($id and
            array_key_exists('password', $values) and
            !$user->getPasswordHasher()->check($values['current_password'], $user->password)
        ) {
            throw new AuthorizationException;
        }

        if ($user instanceof Applicationable) {
            ApplicationableHelper::addApplication($user);
        }
        if (array_key_exists('email', $values)) {
            $values['temporary_email'] = $values['email'];
            if ($id) {
                unset($values['email']);
            }
        }
        $user->fill($values)->save();

        \Event::fire($id ? new Update($user) : new Create($user));

        return $user;
    }

    /**
     * @param $token
     * @param $new_pwd
     * @param $pwd
     * @return User
     * @throws AuthorizationException
     */
    public function changePassword($token, $new_pwd, $pwd)
    {
        /** @var User $user */
        $user = $this->getModel()->findByResetPasswordToken($token);


        return $user->changePassword($new_pwd)->save();
    }
}
