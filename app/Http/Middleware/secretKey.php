<?php

namespace App\Http\Middleware;

use Closure;

class secretKey
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        \Illuminate\Support\Facades\Log::info($request->getClientIp());
        //if($request->getClientIp() != $_SERVER['SERVER_ADDR'] || $request->getClientIp() != '217.107.219.119') return response()->json('Invalid Request');
        return $next($request);
    }
}
