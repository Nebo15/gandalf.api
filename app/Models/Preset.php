<?php
/**
 * Author: Paul Bardack paul.bardack@gmail.com http://paulbardack.com
 * Date: 22.02.16
 * Time: 18:42
 */

namespace App\Models;

/**
 * Class Preset
 *
 * @package App\Models
 * @property string $field_key
 * @property string $condition
 * @property string $value
 */
class Preset extends Base
{
    protected $visible = ['field_key', 'condition', 'value'];
}
