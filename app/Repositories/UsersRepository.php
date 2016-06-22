<?php
/*
 * This code was generated automatically by Nebo15/REST
 */

namespace App\Repositories;

use App\Models\User;
use Nebo15\REST\AbstractRepository;

/**
 * Class UsersRepository
 * @package App\Repositories
 * @method User getModel()
 * @method User createOrUpdate($values, $id = null)
 */
class UsersRepository extends AbstractRepository
{
    protected $modelClassName = 'App\Models\User';
    protected $observerClassName = 'App\Observers\UserObserver';
}
