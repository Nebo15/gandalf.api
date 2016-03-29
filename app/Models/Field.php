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
 * @property integer $index - technical property
 * @property Preset $preset
 */
class Field extends Base
{
    protected $fillable = ['key', 'title', 'source', 'type'];

    protected $visible = ['key', 'title', 'source', 'type', 'preset'];

    protected $attributes = [
        'preset' => null
    ];

    protected $casts = [
        '_id' => 'string',
    ];

    protected function getArrayableRelations()
    {
        return ['preset' => $this->preset ?: null];
    }

    public function preset()
    {
        return $this->embedsOne('App\Models\Preset');
    }

    public function setKeyAttribute($value)
    {
        $this->attributes['key'] = strtolower(str_replace(' ', '_', trim($value)));
    }
}
