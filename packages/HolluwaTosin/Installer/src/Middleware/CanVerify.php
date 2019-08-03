<?php
/**
 * Created by PhpStorm.
 * User: HolluwaTosin
 * Date: 6/9/2018
 * Time: 10:21 AM
 */

namespace HolluwaTosin\Installer\Middleware;

use Closure;
use HolluwaTosin\Installer\Installer;
use HolluwaTosin\Installer\PurchaseDetails;

class CanVerify
{
    /**
     * @var Installer
     */
    protected $installer;

    /**
     * CanVerify constructor.
     */
    public function __construct()
    {
        $this->installer = resolve('installer');
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $details = $this->installer->purchaseDetails();

        if(!$this->installed()){
            return redirect()->route('Installer::overview.index');
        }

        if ($details instanceof PurchaseDetails) return abort(404);

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
