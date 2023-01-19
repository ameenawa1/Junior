<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Auth;
use PHPOpenSourceSaver\JWTAuth;
use App\Models\User;


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
        $cookie=$_COOKIE['Authorization'];
        $cookie = str_replace('Bearer ', '',$cookie);
        JWTAuth::toUser($cookie);

        if (! $request->expectsJson()) {
            return route('home');
            dd('auth');
        }
            dd('noauth');
        return $next($request);

    }



}
