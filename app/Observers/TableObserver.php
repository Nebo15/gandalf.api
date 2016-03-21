<?php
/*
 * This code was generated automatically by Nebo15/REST
 */

namespace App\Observers;

use \App\Models\Table;
use App\Repositories\GroupsRepository;
use App\Repositories\TablesRepository;
use Nebo15\Changelog\Changelog;
use Nebo15\LumenApplicationable\ApplicationableHelper;

class TableObserver
{
    public function creating(Table $table)
    {
    }

    public function created(Table $table)
    {
    }

    public function updating(Table $table)
    {
    }

    public function updated(Table $table)
    {
        $groups = (new GroupsRepository)->getGroupsByTableId($table->getKey());
        if ($groups->count() > 0) {
            $tablesIds = [];
            foreach ($groups as $group) {
                array_merge($tablesIds, array_column($group->tables, '_id'));
            }
            unset($tablesIds[$table->getKey()]);
            if (count($tablesIds) > 0) {
                $fields = $table->getFieldsKeys()->toArray();
                $tableRepository = new TablesRepository;
                $groupTable = $tableRepository->read($tablesIds[0]);
                $groupFields = $groupTable->getFieldsKeys()->toArray();

                if($diff = array_diff($fields, $groupFields)){

                }
            }
        }
    }

    public function saving(Table $table)
    {
        ApplicationableHelper::addApplication($table);
    }

    public function saved(Table $table)
    {
        /** get user name */
        Changelog::createFromModel($table, 'admin');
    }

    public function deleting(Table $table)
    {
    }

    public function deleted(Table $table)
    {
    }

    public function restoring(Table $table)
    {
    }

    public function restored(Table $table)
    {
    }
}
