<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Log;

class AuthorizePublicIp
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
        $settings = platformSettings();

        if(!in_array($request->ip(), $settings->allowedPublicIps())){
            return abort(403, __('Your IP is not whitelisted!'));
        }

        return $next($request);
    }
}
