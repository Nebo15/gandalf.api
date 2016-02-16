<?php
/**
 * Author: Paul Bardack paul.bardack@gmail.com http://paulbardack.com
 * Date: 16.02.16
 * Time: 17:46
 */

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Auth\Access\AuthorizationException;

class AuthTokenUser
{
    public function handle(Request $request, Closure $next)
    {
        $auth = $request->header('authorization');
        if (!$auth or false === strpos($auth, ':')) {
            throw new AuthorizationException;
        }
        list($app, $token) = array_map('trim', explode(':', $auth));

        $tokens = config('tokens');
        foreach ($tokens as $role => $credentials) {
            if (array_key_exists($app, $credentials) and $credentials[$app] === $token) {
                return $next($request);
            }
        }

        throw new AuthorizationException;
    }
}
