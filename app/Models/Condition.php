<?php
/**
 * Author: Paul Bardack paul.bardack@gmail.com http://paulbardack.com
 * Date: 15.02.16
 * Time: 17:40
 */

namespace App\Models;

/**
 * Class Condition
 * @package App\Models
 * @property string $field_key
 * @property string $condition
 * @property string $value
 * @property bool $matched
 */
class Condition extends Base
{
    protected $visible = ['field_key', 'condition', 'value'];

    protected $fillable = ['field_key', 'condition', 'value'];

    public function setFieldKeyAttribute($value)
    {
        $this->attributes['field_key'] = strtolower(str_replace(' ', '_', trim($value)));
    }
}
