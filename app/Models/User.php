<?php

namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Laravel\Lumen\Auth\Authorizable;
use Nebo15\LumenOauth2\Traits\Oauthable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Nebo15\LumenOauth2\Interfaces\Oauthable as OauthableContract;

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
