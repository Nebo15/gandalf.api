<?php
/*
 * This code was generated automatically by Nebo15/REST
 */

namespace App\Observers;

use \App\Models\Table;
use App\Repositories\GroupsRepository;
use App\Repositories\TablesRepository;
use Nebo15\Changelog\Changelog;

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
        var_dump($groups->count());die();
        if ($groups->count() > 0) {
            $tablesIds = [];
            foreach ($groups as $group) {
                array_merge($tablesIds, array_column($group->tables, '_id'));
            }
            unset($tablesIds[$table->getKey()]);
            if (count($tablesIds) > 0) {
                $tablesToUpdate = [];
                $originalTableFields = $table->getFieldsKeys()->toArray();
                $tableRepository = new TablesRepository;
                print_r($originalTableFields);die();

                foreach ($tableRepository->findByIds($tablesIds) as $groupTable) {
                    $groupFields = $groupTable->getFieldsKeys()->toArray();

                    if($newFields = array_diff($originalTableFields, $groupFields)){

                    }
                    if($oldFields = array_diff($groupFields, $originalTableFields)){

                    }
                }

            }
        }
    }

    public function saving(Table $table)
    {
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
