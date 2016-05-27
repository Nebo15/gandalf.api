<?php
namespace App\Models;

class Invitations extends Base
{

    protected $attributes = [
        'email' => '',
        'project' => [],
        'role' => '',
        'scope' => [],
    ];

    protected $fillable = ['email', 'project', 'role', 'scope'];

}