<?php
/**
 * Author: Paul Bardack paul.bardack@gmail.com http://paulbardack.com
 * Date: 12.02.16
 * Time: 17:39
 */

namespace App\Models;

/**
 * Class Decision
 * @package App\Models
 * @property array $fields
 * @property Rule[] $rules
 */
class Decision extends Base
{
    protected $visible = ['_id', 'fields', 'rules'];

    protected $fillable = ['fields', 'rules'];
}
