<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Auth\Access\AuthorizationException;

class AuthTokenAdmin
{
    public function handle(Request $request, Closure $next)
    {
        $auth = $request->header('authorization');
        if (!$auth or false === strpos($auth, ':')) {
            throw new AuthorizationException;
        }
        list($app, $token) = array_map('trim', explode(':', $auth));

        $tokens = config('tokens.admin');
        if (!array_key_exists($app, $tokens) or $tokens[$app] !== $token) {
            throw new AuthorizationException;
        }

        return $next($request);
    }
}
