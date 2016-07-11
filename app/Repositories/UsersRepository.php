<?php
/*
 * This code was generated automatically by Nebo15/REST
 */

namespace App\Repositories;

use App\Models\User;
use App\Events\Users\Create;
use App\Events\Users\Update;
use Nebo15\REST\AbstractRepository;
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

        if ($user instanceof Applicationable) {
            ApplicationableHelper::addApplication($user);
        }

        if (array_key_exists('email', $values) and !env('ACTIVATE_ALL_USERS')) {
            $values['temporary_email'] = $values['email'];
            if ($id) {
                unset($values['email']);
            }
            $user->createVerifyEmailToken();
        }

        $user->fill($values)->save();

        \Event::fire($id ? new Update($user) : new Create($user));

        return $user;
    }
}
