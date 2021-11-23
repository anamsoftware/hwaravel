<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    protected function redirectTo($request)
    {
        if (! $request->expectsJson()) {
            if (request()->is(hwa_admin_dir() . '/*') || request()->is(hwa_admin_dir())) {
                return route('admin.auth.login');
            }
            return route('client.login');
        }
    }
}
