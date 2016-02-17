<?php
/**
 * Author: Paul Bardack paul.bardack@gmail.com http://paulbardack.com
 * Date: 12.02.16
 * Time: 18:12
 */

namespace App\Validators;

class DecisionStructValidator
{
    public function decision($attribute, $value)
    {
        if (!is_array($value)
            or !isset($value['default_decision'])
            or !isset($value['fields'])
            or !isset($value['rules'])
            or !is_array($value['rules'])
        ) {
            return false;
        }

        $fields = ['title', 'key', 'type', 'source'];
        foreach ($value['fields'] as $request_field) {
            foreach ($fields as $field) {
                if (!array_key_exists($field, $request_field)) {
                    return false;
                }
            }
        }

        $table_fields_keys = array_map(function ($value) {
            return $value['key'];
        }, $value['fields']);

        $rules_fields = ['than', 'description', 'conditions'];
        $condition_fields = ['field_key', 'condition', 'value'];
        foreach ($value['rules'] as $item) {
            if (!is_array($item)) {
                return false;
            }

            foreach ($rules_fields as $key) {
                if (!array_key_exists($key, $item)) {
                    return false;
                }
            }

            if (!is_array($item['conditions'])) {
                return false;
            }
            foreach ($item['conditions'] as $condition) {
                if (count($condition_fields) != count($condition)) {
                    return false;
                }

                foreach ($condition_fields as $table_field) {
                    if (!array_key_exists($table_field, $condition)) {
                        return false;
                    }
                    if (!in_array($condition['field_key'], $table_fields_keys)) {
                        return false;
                    }
                }
            }
        }

        return true;
    }
}
