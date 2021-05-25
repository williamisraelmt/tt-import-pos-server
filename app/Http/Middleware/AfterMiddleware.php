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
use Symfony\Component\HttpFoundation\StreamedResponse;

class AfterMiddleware
{
    public function handle($request, Closure $next)
    {
        /** @var StreamedResponse $response */
        $response = $next($request);
        if ($response && $request->expectsJson()) {
            // Perform action
            try {
                $response->setData(CamelCaseUtil::keysToCamelCase($response->getData()));
            } catch (\Exception $e) {
            }
        }
        return $response;
    }
}
