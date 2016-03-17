<?php
/*
 * This code was generated automatically by Nebo15/REST
 */

namespace App\Repositories;

use Nebo15\REST\AbstractRepository;

class UsersRepository extends AbstractRepository
{
    protected $modelClassName = 'App\Models\User';
    protected $observerClassName = 'App\Observers\UserObserver';
}
