<?php

namespace Modules\Base\Http\Middleware;

use Illuminate\Http\Response;

class NoAjax
{
    
    /**
     * constructor.
     */
    public function __construct()
    {
    }

    /**
     * @param $request
     * @param \Closure $next
     * @return \Illuminate\Http\RedirectResponse|Response
     */
    public function handle($request, \Closure $next)
    {
        if ($request->ajax() && $request->method()!='GET') {
            return response('Unauthorized.', Response::HTTP_UNAUTHORIZED);
        }
        
        return $next($request);
    }

}
