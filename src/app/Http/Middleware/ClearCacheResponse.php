<?php

namespace Starmoozie\MenuPermission\app\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Spatie\ResponseCache\Middlewares\CacheResponse as CacheResponseBase;
use Spatie\ResponseCache\Facades\ResponseCache;

class ClearCacheResponse extends CacheResponseBase
{
    /**
     * Bypass cache middleware after form submitted or when a defined flashed session data is set.
     *
     * @param  Request  $request
     * @param  Closure  $next
     *
     * @return Response
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (\Alert::count()) {
            ResponseCache::forget($request->url());
            return parent::handle($request, $next, 0);
        }

        return $next($request);
    }
}