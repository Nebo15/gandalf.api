<?php
namespace App\Models;

class Invitation extends Base
{

    protected $attributes = [
        'email' => '',
        'project' => [],
        'role' => '',
        'scope' => [],
    ];

    protected $fillable = ['email', 'project', 'role', 'scope'];
}
