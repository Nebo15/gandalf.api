<?php
/*
 * This code was generated automatically by Nebo15/REST
 */

namespace App\Repositories;

use App\Models\User;
use App\Events\Users\Create;
use App\Events\Users\Update;
use Nebo15\REST\AbstractRepository;

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
        $user = parent::createOrUpdate($values, $id);
        \Event::fire($id ? new Update($user) : new Create($user));

        return $user;
    }
}
