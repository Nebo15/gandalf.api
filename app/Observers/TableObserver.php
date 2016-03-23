<?php
/*
 * This code was generated automatically by Nebo15/REST
 */

namespace App\Observers;

use App\Models\Field;
use \App\Models\Table;
use App\Models\Condition;
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
        if ($groups->count() > 0) {
            $tablesIds = [];
            foreach ($groups as $group) {
                $tablesIds = array_merge($tablesIds, array_column($group->tables, '_id'));
            }
            unset($tablesIds[$table->getKey()]);
            if (count($tablesIds) > 0) {

                /** @var Table[] $tablesToUpdate */
                $tablesToUpdate = [];
                $originalTableFields = [];
                $originalTableFieldsKeys = [];

                foreach ($table->fields()->toArray() as $field) {
                    $originalTableFieldsKeys[] = $field['key'];
                    $originalTableFields[$field['key']] = $field;
                }

                $tableRepository = new TablesRepository;
                foreach ($tableRepository->findByIds($tablesIds) as $groupTable) {
                    $groupFields = [];
                    $groupFieldsKeys = [];
                    /** @var Field $field */
                    foreach ($groupTable->fields()->get() as $field) {
                        $groupFieldsKeys[] = $field->key;
                        $groupFields[$field->key] = $field;
                    }

                    $newFieldsKeys = array_diff($originalTableFieldsKeys, $groupFieldsKeys);
                    $oldFieldsKeys = array_diff($groupFieldsKeys, $originalTableFieldsKeys);
                    if ($newFieldsKeys) {
                        foreach ($newFieldsKeys as $newFieldKey) {
                            $groupTable->fields()->associate(new Field($originalTableFields[$newFieldKey]));
                            $tablesToUpdate[] = $groupTable;
                        }
                    }
                    if ($oldFieldsKeys) {
                        $toRemove = [];
                        foreach ($oldFieldsKeys as $oldFieldKey) {
                            $toRemove[] = $groupFields[$oldFieldKey]->_id;
                        }
                        $groupTable->fields()->dissociate($toRemove);
                        $tablesToUpdate[] = $groupTable;
                    }

                    if ($newFieldsKeys or $oldFieldsKeys) {
                        /** @var \App\Models\Rule $rule */
                        foreach ($groupTable->rules()->get() as $rule) {
                            if ($newFieldsKeys) {
                                foreach ($newFieldsKeys as $newFieldKey) {
                                    $rule->conditions()->associate(new Condition([
                                        'field_key' => $newFieldKey,
                                        'condition' => '$is_set',
                                        'value' => true
                                    ]));
                                }
                            }
                            if ($oldFieldsKeys) {
                                foreach ($oldFieldsKeys as $oldFieldKey) {
                                    $conditionsToRemove = [];
                                    /** @var Condition $condition */
                                    foreach ($rule->conditions()->get() as $condition) {
                                        if ($condition->field_key == $oldFieldKey) {
                                            $conditionsToRemove = $condition->_id;
                                        }
                                    }
                                    $rule->conditions()->dissociate($conditionsToRemove);
                                }
                            }
                            $groupTable->rules()->associate($rule);
                        }
                    }
                }
                if ($tablesToUpdate) {
                    # ToDo: here should be Multiple Update
                    foreach ($tablesToUpdate as $item) {
                        $item->save();
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
