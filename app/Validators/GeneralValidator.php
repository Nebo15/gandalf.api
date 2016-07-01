<?php
/**
 * Author: Paul Bardack paul.bardack@gmail.com http://paulbardack.com
 * Date: 01.07.16
 * Time: 11:50
 */

namespace App\Validators;

class GeneralValidator
{
    public function mongoId($attribute, $value)
    {
        return preg_match('/^(?=[a-f\d]{24}$)(\d+[a-f]|[a-f]+\d)/', $value);
    }

    public function json($attribute, $value)
    {
        return boolval(json_decode($value, true));
    }

    public function betweenString($attribute, $value)
    {
        if (strpos($value, ';') === false) {
            return false;
        }

        $between = array_map(function ($item) {
            return floatval(str_replace(',', '.', $item));
        }, explode(';', $value));

        if (count($between) > 2) {
            return false;
        }
        if (!is_numeric($between[0]) or !is_numeric($between[1])) {
            return false;
        }

        return $between[0] < $between[1];
    }
}
