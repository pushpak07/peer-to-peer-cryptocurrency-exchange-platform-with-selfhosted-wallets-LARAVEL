<?php

namespace App\Http\Controllers\Profile;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use PragmaRX\Google2FA\Google2FA;

class TwoFactorController extends Controller
{
    /**
     * An instance of google 2fa generator
     *
     * @var Google2FA
     */
    protected $google2fa;

    /**
     * Create a new controller instance.
     *
     * @param Google2FA $google2fa
     * @return void
     */
    public function __construct(Google2FA $google2fa)
    {
        $this->google2fa = $google2fa;
    }

    /**
     * Setup two factor authentication
     *
     * @param Request $request
     * @param User $user
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function setup(Request $request, User $user)
    {
        if ($request->ajax()) {
            $code =  (string) $request->code ?: "";

            $valid = $this->google2fa->verifyKey($user->google2fa_secret, $code);

            if ($valid) {
                $setting = $user->getSetting();
                $setting->google2fa_status = true;
                $setting->save();

                return response(__("Your setup was successful!"));
            }

            return response(__("The token you entered was invalid!"), 403);

        } else {
            return abort(403);
        }
    }

    /**
     * Reset two factor settings
     *
     * @param Request $request
     * @param User $user
     * @return \Illuminate\Http\RedirectResponse
     */
    public function reset(Request $request, User $user)
    {
        $setting = $user->getSetting();
        $setting->user_login_2fa = false;
        $setting->outgoing_transfer_2fa = false;
        $setting->google2fa_status = false;

        $setting->save();

        $secret = $this->google2fa->generateSecretKey();

        $user->google2fa_secret = $secret;
        $user->save();

        toastr()->success(__("Your reset was successful!"));

        return redirect()->back();
    }
}
