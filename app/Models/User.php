<?php

namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Laravel\Lumen\Auth\Authorizable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Nebo15\LumenOauth2\Interfaces\Oauthable as OauthableContract;
use Nebo15\LumenOauth2\Traits\Oauthable;

class User extends Base implements
    AuthenticatableContract,
    AuthorizableContract,
    OauthableContract
{
    use Authenticatable, Authorizable, Oauthable;

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'password',
    ];
}
