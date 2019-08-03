<?php
/**
 * ======================================================================================================
 * File Name: Verification.php
 * ======================================================================================================
 * Author: HolluwaTosin360
 * ------------------------------------------------------------------------------------------------------
 * Portfolio: http://codecanyon.net/user/holluwatosin360
 * ------------------------------------------------------------------------------------------------------
 * Date & Time: 10/20/2018 (10:15 PM)
 * ------------------------------------------------------------------------------------------------------
 *
 * Copyright (c) 2018. This project is released under the standard of CodeCanyon License.
 * You may NOT modify/redistribute this copy of the project. We reserve the right to take legal actions
 * if any part of the license is violated. Learn more: https://codecanyon.net/licenses/standard.
 *
 * ------------------------------------------------------------------------------------------------------
 */

namespace HolluwaTosin\Installer\Middleware;

use Closure;
use GuzzleHttp\Exception\ClientException;
use HolluwaTosin\Installer\Installer;
use HolluwaTosin\Installer\PurchaseDetails;
use Illuminate\Cache\Repository as Cache;
use Illuminate\Support\Facades\File;

class Verification
{
    /**
     * The URIs that should be excluded from License verification.
     * This is necessary for Install & Verify Routes.
     *
     * @var array
     */
    protected $except = [
        'install*', 'verify*',
    ];

    /**
     * @var Installer
     */
    protected $installer;

    /**
     * Verification constructor.
     */
    public function __construct()
    {
        $this->installer = resolve('installer');
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @param   string|null
     * @return mixed
     */
    public function handle($request, Closure $next, $type = null)
    {
        if (!$this->inExceptArray($request)) {
            if (!$this->installed()) {
                return redirect()->route('Installer::overview.index');
            }

            if ($code = $this->installer->getVerificationCode()) {

                $details = $this->installer->details($code);

                if (!is_object($details)) {

                    if (is_array($details) && isset($details['error'])) {
                        return redirect()->route('Installer::verify.index')
                            ->with('message', $details['message']);
                    } else {
                        return redirect()->route('Installer::verify.index');
                    }

                }

                if (!$this->checkLicense($details, $type)) {
                    return abort(403);
                }

                view()->share(['purchaseDetails' => $details]);

            } else {
                return redirect()->route('Installer::verify.index');
            }
        }

        return $next($request);
    }

    /**
     * Validate middleware license
     *
     * @param PurchaseDetails $details
     * @param $type
     * @return bool
     */
    public function checkLicense($details, $type)
    {
        $value = true;

        if ($type !== null) {
            if ($details->isRegularLicense()) {
                $value = ($type == 'regular');
            } elseif ($details->isExtendedLicense()) {
                $value = ($type == 'extended');
            } else {
                $value = false;
            }
        }

        return $value;
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

    /**
     * Determine if the request has a URI that should be excluded.
     *
     * @param  \Illuminate\Http\Request $request
     * @return bool
     */
    protected function inExceptArray($request)
    {
        foreach ($this->except as $except) {
            if ($except !== '/') {
                $except = trim($except, '/');
            }

            if ($request->fullUrlIs($except) || $request->is($except)) {
                return true;
            }
        }

        return false;
    }
}
