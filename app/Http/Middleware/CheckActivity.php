<?php

namespace App\Http\Middleware;

use App\Events\UserPresenceUpdated;
use App\Models\User;
use Closure;
use Illuminate\Support\Facades\Auth;

class CheckActivity
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
    	if(Auth::check()){
		    if(Auth::user()->status == 'inactive'){
			    $user = Auth::user();

			    $user->presence = 'offline';
			    $user->save();

			    broadcast(new UserPresenceUpdated($user));

			    Auth::logout();

			    return redirect('/');
		    }
	    }

        return $next($request);
    }
}
