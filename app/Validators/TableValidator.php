<?php
/**
 * Author: Paul Bardack paul.bardack@gmail.com http://paulbardack.com
 * Date: 12.02.16
 * Time: 18:12
 */

namespace App\Validators;

use App\Services\ConditionsTypes;
use Illuminate\Validation\Validator;
use App\Exceptions\ConditionException;

class TableValidator
{
    private $conditionsTypes;

    public function __construct(ConditionsTypes $conditionsTypes)
    {
        $this->conditionsTypes = $conditionsTypes;
    }

    public function conditionType($attribute, $value, $parameters, Validator $validator)
    {
        try {
            $condition = $this->conditionsTypes->getCondition(
                array_get(
                    $validator->getData(),
                    str_replace('value', 'condition', $attribute)
                )
            );
        } catch (ConditionException $e) {
            return false;
        }

        if ($type = $condition['input_type']) {
            $validator = \Validator::make(
                ['value' => $value],
                ['value' => "required|$type"]
            );
            return !($validator->fails());
        }

        return true;
    }

    public function ruleThanType($attribute, $value, $parameters, Validator $validator)
    {
        $type = 'alpha_dash';
        $rule_matching = array_get($validator->getData(), 'table.matching_type', 'first');
        if ($rule_matching == 'all') {
            $type = 'numeric';
        }
        $validator = \Validator::make(
            ['value' => $value],
            ['value' => "required|$type"]
        );

        return !($validator->fails());
    }

    public function conditionsCount($attribute, $value, $parameters, Validator $validator)
    {
        $fields = array_get($validator->getData(), 'table.fields');

        $unique_fields = [];
        $i = 0;
        foreach ($fields as $field) {
            $key = isset($field['key']) ? $field['key'] : $i;
            $unique_fields[$key] = $i;
            $i++;
        }

        $unique_conditions = [];
        $n = 0;
        foreach ($value as $condition) {
            $key = isset($condition['field_key']) ? $condition['field_key'] : $n;
            $unique_conditions[$key] = $n;
            $n++;
        }

        return count($unique_conditions) == count($unique_fields);
    }

    public function conditionsField($attribute, $value, $parameters, Validator $validator)
    {
        $data = $validator->getData();

        if (!isset($data['table']['fields'])
            or !is_array($data['table']['fields'])
            or count($data['table']['fields']) <= 0
        ) {
            return false;
        }

        foreach ($data['table']['fields'] as $field) {
            if (!isset($field['key'])) {
                return false;
            }
            if ($field['key'] == $value) {
                return true;
            }
        }

        return false;
    }
}
