<?php
/**
 * Created by PhpStorm.
 * User: Williams Martinez
 * Date: 3/9/2019
 * Time: 10:28 PM
 */

namespace App\Http\Middleware;

use App\Utils\CamelCaseUtil;
use Closure;

class AfterMiddleware
{
    public function handle($request, Closure $next)
    {
        $response = $next($request);
        // Perform action
        try {
            $response->setData(CamelCaseUtil::keysToCamelCase($response->getData()));
        } catch (\Exception $e) {
        }
        return $response;
    }
}
