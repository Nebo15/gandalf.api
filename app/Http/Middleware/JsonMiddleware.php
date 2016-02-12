<?php

namespace App\Http\Middleware;

use Closure;

class JsonMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (!$request->has('accept')) {
            $request->headers->set('Accept', 'application/json');
        }
        if (!$request->has('content-type')) {
            $request->headers->set('Content-Type', 'application/json');
        }

        return $next($request);
    }
}
