<?php

namespace App\Http\Controllers\Auth;

use App\Events\UserPresenceUpdated;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use PragmaRX\Google2FA\Google2FA;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/home';

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
        $this->middleware('guest')->except('logout');

        $this->google2fa = $google2fa;
    }

    /**
     * Show the application's login form.
     *
     * @return \Illuminate\Http\Response
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Validate the user login request.
     *
     * @param  \Illuminate\Http\Request $request
     * @return void
     */
    protected function validateLogin(Request $request)
    {
        $rules = [
            $this->username() => 'required|string',
            'password' => 'required|string'
        ];

        $user = User::where('name', $request->name)->first();

        if ($user && (bool) $user->getSetting()->user_login_2fa) {

            $rules = array_merge($rules, [
                'token' => [
                    'required', function ($attribute, $value, $fail) use ($user) {
                        if ($value !== null) {
                            $valid = $this->google2fa->verifyKey(
                                $user->google2fa_secret, $value
                            );

                            if (!$valid) {
                                $fail(__('You have entered an invalid token!'));
                            }
                        } else {
                            $fail(__('Two factor authentication is required!'));
                        }
                    },
                ]
            ]);

        }

        if (config()->get('services.nocaptcha.enable')) {
            $rules = array_merge($rules, [
                'g-recaptcha-response' => 'required|recaptcha'
            ]);
        }

        $this->validate($request, $rules, [
            'token.required' => __('Please enter your 2FA token!')
        ]);
    }

	/**
     * Get the failed login response instance.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function sendFailedLoginResponse(Request $request)
    {
        if (!$request->has('token')) {

            $messages = [
                $this->username() => [trans('auth.failed')],
            ];

        } else {

            $messages = [
                'token' => [trans('auth.failed')]
            ];

        }

        throw ValidationException::withMessages($messages);
    }


    /**
     * The user has been authenticated.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  mixed $user
     * @return mixed
     */
    protected function authenticated(Request $request, $user)
    {
        if ($user->status != 'active') {
            $messages = [
                $this->username() => [trans('auth.deactivated')],
            ];

            $this->guard()->logout();

            throw ValidationException::withMessages($messages);
        }

	    userActivity()->log('User login');

	    return redirect()->intended($this->redirectPath());
    }

    /**
     * Get the login username to be used by the controller.
     *
     * @return string
     */
    public function username()
    {
        return 'name';
    }


    public function check2FA(Request $request)
    {
        if ($request->ajax()) {
            $this->validate($request, [
                'name' => 'required|exists:users,name'
            ]);

            $user = User::where('name', $request->name)->first();

            $status = (bool) $user->getSetting()->user_login_2fa;

            return response()->json(['status' => $status]);
        } else {
            return abort(404);
        }
    }

    /**
     * Log the user out of the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function logout(Request $request)
    {
        $user = User::where('id', Auth::id())->first();

        $user->presence = 'offline';
        $user->save();

	    userActivity()->log('User logout');

	    $this->guard()->logout();

        broadcast(new UserPresenceUpdated($user));

        $request->session()->invalidate();

        return $this->loggedOut($request) ?: redirect('/');
    }
}
