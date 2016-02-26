<?php
/**
 * Author: Paul Bardack paul.bardack@gmail.com http://paulbardack.com
 * Date: 12.02.16
 * Time: 18:12
 */

namespace App\Validators;

use Illuminate\Validation\Validator;

class DecisionStructValidator
{
    public function conditionType($attribute, $value, $parameters, Validator $validator)
    {
        echo gettype($value);
        print_r($validator->getData());
        die();
        return true;
    }

    public function conditionsCount($attribute, $value, $parameters, Validator $validator)
    {
        print_r('asd');
        die();
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
