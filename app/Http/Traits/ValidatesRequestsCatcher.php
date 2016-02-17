<?php
namespace App\Http\Traits;

use Illuminate\Contracts\Validation\ValidationException;
use Illuminate\Http\Request;

trait ValidatesRequestsCatcher
{
    protected function throwValidationException(Request $request, $validator)
    {
        throw new ValidationException($validator);
    }
}
