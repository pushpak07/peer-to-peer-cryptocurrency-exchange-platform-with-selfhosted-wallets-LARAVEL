<?php

namespace HolluwaTosin\Installer\Middleware;

use Closure;
use DB;
use Redirect;

class CanInstall
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     * @return \Illuminate\Http\RedirectResponse
     */
    public function handle($request, Closure $next)
    {
        if (self::installed()) {
            $action = config('installer.installed_action.default');

            switch ($action) {
                case 'route':
                    return redirect()->route(config('installer.installed_action.options.route.name'));
                    break;

                case 'abort':
                default:
                    return abort(config('installer.installed_action.options.abort.type'));
            }
        }

        return $next($request);
    }

    /**
     * If application is already installed.
     *
     * @return bool
     */
    public static function installed()
    {
        return file_exists(storage_path('installed'));
    }
}
