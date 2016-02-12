<?php
/**
 * Author: Paul Bardack paul.bardack@gmail.com http://paulbardack.com
 * Date: 12.02.16
 * Time: 18:12
 */

namespace App\Validators;

class ScoringStructValidator
{
    public function conditionsTypes($attribute, $value)
    {
        if (!is_array($value)) {
            return false;
        }
        foreach ($value as $item) {
            if (!is_object($item) or property_exists($item, 'alias') or !$item->alias) {
                return false;
            }
        }
    }
}
