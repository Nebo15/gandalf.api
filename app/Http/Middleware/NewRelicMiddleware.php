<?php

namespace App\Http\Middleware;

use Closure;

class NewRelicMiddleware
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
        if (extension_loaded('newrelic')) {
            newrelic_name_transaction(sprintf('%s (%s)', $request->getRequestUri(), $request->method()));
        }

        return $next($request);
    }
}
