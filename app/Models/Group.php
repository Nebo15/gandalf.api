<?php
/*
 * This code was generated automatically by Nebo15/REST
 */

namespace App\Models;

use Nebo15\REST\Traits\ListableTrait;
use Nebo15\REST\Interfaces\ListableInterface;

class Group extends Base implements ListableInterface
{
    use ListableTrait;

    protected $fillable = ['tables', 'probability_type'];

    protected $listable = ['tables', 'probability_type'];

    protected $visible = ['tables', 'probability_type'];
}
