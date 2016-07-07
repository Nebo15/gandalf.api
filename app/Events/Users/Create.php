<?php
/**
 * Author: Paul Bardack paul.bardack@gmail.com http://paulbardack.com
 * Date: 07.07.16
 * Time: 13:23
 */

namespace App\Events\Users;

use App\Models\User;

class Create
{
    public $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }
}
