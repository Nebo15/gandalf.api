<?php
/**
 * Author: Paul Bardack paul.bardack@gmail.com http://paulbardack.com
 * Date: 02.03.16
 * Time: 13:12
 */

namespace App\Models;


class Group extends Base implements Listable
{
    protected $fillable = ['tables', 'probability'];

    protected $visible = ['tables', 'probability'];
}
