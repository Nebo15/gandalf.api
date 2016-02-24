<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Auth\Access\AuthorizationException;

class AuthTokenAdmin
{
    public function handle(Request $request, Closure $next)
    {
        $app = $request->getUser();
        $token = $request->getPassword();
        if (!$app or !$token) {
            throw new AuthorizationException;
        }

        $tokens = config('tokens.admin');
        if (!array_key_exists($app, $tokens) or $tokens[$app] !== $token) {
            throw new AuthorizationException;
        }

        return $next($request);
    }
}
