<?php
/**
 * Author: Paul Bardack paul.bardack@gmail.com http://paulbardack.com
 * Date: 02.03.16
 * Time: 13:12
 */

namespace App\Models;

use App\Http\Traits\RESTListable as ListableTrait;

class Group extends Base implements RESTListable
{
    use ListableTrait;

    protected $fillable = ['tables', 'probability'];

    protected $listable = ['probability'];

    protected $visible = ['_id', 'tables', 'probability'];
}
