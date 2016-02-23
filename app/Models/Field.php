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
 * @property string $alias
 * @property string $source
 * @property string $type
 * @property Preset $preset
 */
class Field extends Base
{
    protected $fillable = ['key', 'alias', 'title', 'source', 'type', 'preset'];

    protected $visible = ['key', 'alias', 'title', 'source', 'type', 'preset'];

    public function preset()
    {
        return $this->embedsOne('App\Models\Preset');
    }
}
