<?php
/*
 * This code was generated automatically by Nebo15/REST
 */
namespace App\Models;

use App\Exceptions\TokenExpiredException;
use App\Exceptions\TokenNotFoundException;
use App\Services\Hasher;
use Illuminate\Auth\Authenticatable;
use Laravel\Lumen\Auth\Authorizable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Nebo15\LumenApplicationable\Contracts\ApplicationableUser as ApplicationableUserContract;
use Nebo15\LumenApplicationable\Traits\ApplicationableUserTrait;
use Nebo15\LumenOauth2\Interfaces\Oauthable as OauthableContract;
use Nebo15\LumenOauth2\Traits\Oauthable;
use Nebo15\REST\Traits\ListableTrait;
use Nebo15\REST\Interfaces\ListableInterface;

/**
 * Class User
 * @package App\Models
 * @property string $username
 * @property string $first_name
 * @property string $last_name
 * @property string $email
 * @property string $password
 * @property string $temporary_email
 * @property bool $active
 * @property array $tokens
 */
class User extends Base implements
    ListableInterface,
    AuthenticatableContract,
    AuthorizableContract,
    OauthableContract,
    ApplicationableUserContract
{
    use ListableTrait, Authenticatable, Authorizable, Oauthable, ApplicationableUserTrait;

    protected $listable = [
        '_id',
        'username',
        'first_name',
        'last_name',
    ];

    protected $visible = ['_id', 'username', 'temporary_email', 'email', 'first_name', 'last_name', 'active'];

    protected $fillable = ['username', 'temporary_email', 'email', 'active', 'password', 'first_name', 'last_name'];

    protected $attributes = [
        'active' => false,
        'email' => '',
        'temporary_email' => '',
        'tokens' => [],
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'password',
    ];

    public function createVerifyEmailToken()
    {
        $this->attributes['tokens']['verify_email'] = [
            'token' => Hasher::getToken(),
            'expired' => time() + 3600,
        ];

        return $this;
    }

    public function createResetPasswordToken()
    {
        $this->attributes['tokens']['reset_password'] = [
            'token' => Hasher::getToken(),
            'expired' => time() + 3600,
        ];

        return $this;
    }

    public function getVerifyEmailToken()
    {
        return $this->getInternalToken('verify_email');
    }

    public function getResetPasswordToken()
    {
        return $this->getInternalToken('reset_password');
    }

    public function removeVerifyEmailToken()
    {
        unset($this->attributes['tokens']['verify_email']);

        return $this;
    }

    public function verifyEmail()
    {
        $this->email = $this->temporary_email;
        $this->temporary_email = null;
        $this->active = true;
        $this->removeVerifyEmailToken();

        return $this;
    }

    public function changePassword($new_password)
    {
        $this->setAttribute('password', $new_password);
        return $this;
    }

    /**
     * @param $token
     * @return User
     */
    public function findByVerifyEmailToken($token)
    {
        return $this->findByToken($token, 'verify_email');
    }

    /**
     * @param $token
     * @return User
     */
    public function findByResetPasswordToken($token)
    {
        return $this->findByToken($token, 'reset_password');
    }

    public function findByToken($token, $type, $field = null, $value = null)
    {
        $query = $this->where("tokens.$type.token", '=', $token);
        if ($field and $value) {
            $query->where($field, '=', $value);
        }
        if (!$user = $query->first()) {
            throw new TokenNotFoundException;
        }
        if ($user->tokens[$type]['expired'] <= time()) {
            throw new TokenExpiredException;
        }

        return $user;
    }

    public function isActive()
    {
        return $this->active;
    }

    private function getInternalToken($type)
    {
        return (array_key_exists($type, $this->attributes['tokens'])) ?
            $this->attributes['tokens'][$type] :
            false;
    }
}
