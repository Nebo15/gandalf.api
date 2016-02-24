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

class AuthTokenConsumer
{
    public function handle(Request $request, Closure $next)
    {
        $app = $request->getUser();
        $token = $request->getPassword();
        if (!$app or !$token) {
            throw new AuthorizationException;
        }

        $tokens = config('tokens');
        foreach ($tokens as $role => $credentials) {
            if (array_key_exists($app, $credentials) and $credentials[$app] === $token) {
                return $next($request);
            }
        }

        throw new AuthorizationException;
    }
}
