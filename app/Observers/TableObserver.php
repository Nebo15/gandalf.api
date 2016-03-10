<?php
/*
 * This code was generated automatically by Nebo15/REST
 */

namespace App\Observers;

use \App\Models\Table;
use Nebo15\Changelog\Changelog;

class TableObserver
{
    public function creating(Table $table)
    {}

    public function created(Table $table)
    {}

    public function updating(Table $table)
    {}

    public function updated(Table $table)
    {}

    public function saving(Table $table)
    {
        Changelog::createFromModel($table, '');
    }

    public function saved(Table $table)
    {}

    public function deleting(Table $table)
    {}

    public function deleted(Table $table)
    {}

    public function restoring(Table $table)
    {}

    public function restored(Table $table)
    {}
}
