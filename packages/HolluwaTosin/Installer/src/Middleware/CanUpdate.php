<?php

namespace HolluwaTosin\Installer\Middleware;

use Closure;
use HolluwaTosin\Installer\Helpers\Traits\MigrationsHelper;

class CanUpdate
{
    use MigrationsHelper;

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if(!(bool) config('installer.enabled_update')){
            return abort(404);
        }

        if (!$this->installed()) {
            return redirect()->route('Installer::overview.index');
        }

        if($this->countPendingMigrations() <= 0) {
            return abort(404);
        }

        return $next($request);
    }

    /**
     * Check if script not installed
     *
     * @return bool
     */
    public function installed()
    {
        return CanInstall::installed();
    }

}
