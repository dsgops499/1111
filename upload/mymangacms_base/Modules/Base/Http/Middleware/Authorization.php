<?php

namespace Modules\Base\Http\Middleware;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\User\Contracts\Authentication;

/**
 * Class Authorization
 * Inspired by : https://github.com/spatie/laravel-authorize
 * @package Modules\Core\Http\Middleware
 */
class Authorization
{
    /**
     * @var Authentication
     */
    private $auth;

    /**
     * Authorization constructor.
     * @param Authentication $auth
     */
    public function __construct(Authentication $auth)
    {
        $this->auth = $auth;
    }

    /**
     * @param $request
     * @param \Closure $next
     * @param $permissions
     * @return \Illuminate\Http\RedirectResponse|Response
     */
    public function handle($request, \Closure $next, $permissions)
    {        
        if ($this->auth->hasAnyAccess(explode('|', $permissions)) === false) {
            return $this->handleUnauthorizedRequest($request);
        }

        return $next($request);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|Response
     */
    private function handleUnauthorizedRequest(Request $request)
    {
        if ($request->ajax()) {
            return response('Unauthorized.', Response::HTTP_UNAUTHORIZED);
        }
        if ($request->user() === null) {
            return redirect()->guest('auth/login');
        }

        return redirect()->back()
            ->withError(trans('base::messages.admin.permission_denied'));
    }
}
