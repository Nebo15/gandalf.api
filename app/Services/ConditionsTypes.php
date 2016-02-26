<?php
/**
 * Author: Paul Bardack paul.bardack@gmail.com http://paulbardack.com
 * Date: 26.02.16
 * Time: 16:36
 */

namespace App\Services;

class ConditionsTypes
{
    private $conditions;

    public function __construct()
    {
        $this->conditions = [
            '$is_set' => [
                'input_type' => '',
                'function' => function () {
                    return true;
                }
            ],
            '$eq' => [
                'input_type' => '',
                'function' => function ($condition_value, $field_value) {
                    return $condition_value === $field_value;
                }
            ],
            '$ne' => [
                'input_type' => '',
                'function' => function ($condition_value, $field_value) {
                    return $condition_value !== $field_value;
                }
            ],
            '$gt' => [
                'input_type' => 'numeric',
                'function' => function ($condition_value, $field_value) {
                    return $field_value > $condition_value;
                    ;
                }
            ],
            '$gte' => [
                'input_type' => 'numeric',
                'function' => function ($condition_value, $field_value) {
                    return $field_value >= $condition_value;
                }
            ],
            '$lt' => [
                'input_type' => 'numeric',
                'function' => function ($condition_value, $field_value) {
                    return $field_value < $condition_value;
                }
            ],
            '$lte' => [
                'input_type' => 'numeric',
                'function' => function ($condition_value, $field_value) {
                    return $field_value <= $condition_value;
                }
            ],
            '$in' => [
                'input_type' => '',
                'function' => function ($condition_value, $field_value) {
                    return in_array($field_value, $this->explodeValue($condition_value));
                }
            ],
            '$nin' => [
                'input_type' => '',
                'function' => function ($condition_value, $field_value) {
                    return !in_array($field_value, $this->explodeValue($condition_value));
                }
            ],
        ];
    }

    public function getConditionsRules()
    {
        return implode(',', array_keys($this->conditions));
    }

    public function checkConditionValue($condition, $condition_value, $field_value)
    {
        if (!array_key_exists($condition, $this->conditions)) {
            throw new \Exception("Undefined condition rule '$condition'");
        }

        return $this->conditions[$condition]['function']($condition_value, $field_value);
    }

    private function explodeValue($value)
    {
        preg_match_all("/'[^']+'|[^, ]+/", $value, $output);

        return array_map(function ($value) {
            return trim($value, "'");
        }, $output[0]);
    }
}
