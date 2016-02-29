<?php
/**
 * Author: Paul Bardack paul.bardack@gmail.com http://paulbardack.com
 * Date: 29.02.16
 * Time: 14:04
 */

namespace App\Http\Services;

use Illuminate\Support\Str;

class Validator extends \Illuminate\Validation\Validator
{
    public function each($attribute, $rules)
    {
        $data = $this->dot($this->initializeAttributeOnData($attribute));

        $pattern = str_replace('\*', '[^\.]+', preg_quote($attribute));

        foreach ($data as $key => $value) {
            if (Str::startsWith($key, $attribute) || (bool) preg_match('/^'.$pattern.'\z/', $key)) {
                foreach ((array) $rules as $ruleKey => $ruleValue) {
                    if (! is_string($ruleKey) || Str::endsWith($key, $ruleKey)) {
                        $this->mergeRules($key, $ruleValue);
                    }
                }
            }
        }
    }

    private function dot($array, $prepend = '')
    {
        $results = [];

        foreach ($array as $key => $value) {
            if (is_array($value)) {
                $results = array_merge($results, $this->dot($value, $prepend.$key.'.'));
                if (preg_match("@table\.rules\.\d+\.conditions@", $prepend.$key)) {
                    $results[$prepend.$key] = $value;
                }
            } else {
                $results[$prepend.$key] = $value;
            }
        }

        return $results;
    }
}
