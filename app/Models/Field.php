<?php
/**
 * Author: Paul Bardack paul.bardack@gmail.com http://paulbardack.com
 * Date: 16.02.16
 * Time: 14:26
 */

namespace App\Models;

/**
 * Class Field
 * @package App\Models
 * @property string $key
 * @property string $title
 * @property string $source
 * @property string $type
 */
class Field extends Base
{
    protected $visible = ['key', 'title', 'source', 'type'];

    protected $fillable = ['key', 'title', 'source', 'type'];

    # mutators

    public function setKeyAttribute($value)
    {
        $this->attributes['key'] = strtolower(str_replace(' ', '_', trim($value)));
    }
}
