<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Http\RedirectResponse;

class Admin
{
    protected $auth;

    public function __construct(Guard $auth)
    {
        $this->auth = $auth;
    }

    public function handle($request, Closure $next)
    {
        if ($this->auth->check())
        {
            if ($this->auth->user()->access == 'admin') {
                return $next($request);
            }
        }

        return new RedirectResponse(url('/'));

    }
}
