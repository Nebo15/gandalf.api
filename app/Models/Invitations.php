<?php
namespace App\Models;

class Invitations extends Base
{

    protected $attributes = [
        'email' => '',
        'project' => [],
        self::CREATED_AT,
        self::UPDATED_AT,
    ];

    protected $fillable = ['email', 'project'];

}