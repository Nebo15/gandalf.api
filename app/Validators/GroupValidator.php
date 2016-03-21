<?php
/**
 * Author: Paul Bardack paul.bardack@gmail.com http://paulbardack.com
 * Date: 18.03.16
 * Time: 17:29
 */

namespace App\Validators;

use App\Models\Field;
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
        $tablesData = $validator->getData()['tables'];

        $tables = $this->tablesRepository->findByIds(array_column($tablesData, '_id'));
        if ($tables->count() < count($tablesData)) {
            $e = new ModelNotFoundException;
            $e->setModel('App\\Model\\Table');
            throw new $e;
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
}
