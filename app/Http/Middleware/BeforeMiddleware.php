<?php

namespace App\Http\Middleware;

use App\Utils\CamelCaseUtil;
use Closure;
use Illuminate\Http\Request;

class BeforeMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        // Perform action
        if (!$request->expectsJson()) {

            return $next($request);

        }

        $parameters = $request->all();

        foreach ($request->all() as $key => &$row) {

            unset($request[$key]);

        }

        $request->merge(CamelCaseUtil::keysToUnderscore($parameters));

        return $next($request);
    }
}
