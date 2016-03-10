<?php
/*
 * This code was generated automatically by Nebo15/REST
 */

namespace App\Repositories;

use Nebo15\REST\AbstractRepository;

class TablesRepository extends AbstractRepository
{
    protected $modelClassName = 'App\Models\Table';

    protected $observerClassName = 'App\Observers\TableObserver';
}
