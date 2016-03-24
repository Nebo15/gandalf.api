<?php
/**
 * Author: Paul Bardack paul.bardack@gmail.com http://paulbardack.com
 * Date: 18.03.16
 * Time: 17:29
 */

namespace App\Validators;

use App\Models\Table;
use Illuminate\Validation\Validator;
use App\Repositories\TablesRepository;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class GroupValidator
{
    private $tablesRepository;

    public function __construct(TablesRepository $tablesRepository)
    {
        $this->tablesRepository = $tablesRepository;
    }

    public function tablesFields($attribute, $value, $parameters, Validator $validator)
    {
        $tables = $this->getTables($validator);

        if ($tables->count() == 1) {
            return true;
        }

        $fieldsAmount = 0;
        $fieldsExpected = [];
        /** @var \App\Models\Table $table */
        foreach ($tables as $table) {
            if (!$fieldsExpected) {
                $fieldsExpected = $table->getFieldsKeys()->toArray();
                $fieldsAmount = count($fieldsExpected);
                continue;
            }
            $tableFields = $table->getFieldsKeys();
            if ($fieldsAmount != $tableFields->count()) {
                return false;
            }
            if (array_diff($fieldsExpected, $tableFields->toArray())) {
                return false;
            }
        }

        return true;
    }

    public function tablesExists($attribute, $value, $parameters, Validator $validator)
    {
        $this->getTables($validator);

        return true;
    }

    private function getTables(Validator $validator)
    {
        $tablesData = $validator->getData()['tables'];
        try {
            $tables = $this->tablesRepository->findByIds(array_column($tablesData, '_id'));
        } catch (\MongoException $e) {
            $this->throw404();
        }

        $tablesAmount = $tables->count();
        if ($tablesAmount < count($tablesData)) {
            $this->throw404();
        }

        return $tables;
    }

    private function throw404()
    {
        $e = new ModelNotFoundException;
        $e->setModel(Table::class);
        throw new $e;
    }
}
