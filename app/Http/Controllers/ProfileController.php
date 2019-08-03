<?php

namespace App\Http\Controllers;

use App\Events\UserPresenceUpdated;
use App\Models\TradeChat;
use App\Models\User;
use App\Notifications\Verification\PhoneVerification;
use App\Traits\ThrottlesEmails;
use App\Traits\ThrottlesSms;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Intervention\Image\Facades\Image;
use Lunaweb\EmailVerification\EmailVerification;

class ProfileController extends Controller
{
    use ThrottlesEmails, ThrottlesSms;

    /**
     * Get profile picture
     *
     * @param $user_name
     * @param $picture
     * @return mixed
     */
    public function picture($user_name, $picture)
    {
        if($user = User::withTrashed()->where('name', $user_name)->first()){
            return Image::make(getAvatarPath($user, $picture))->response();
        }else{
            return abort(404);
        }
    }

    /**
     * @param Request $request
     * @param User $user
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function resendVerificationSms(Request $request, User $user)
    {
        if ($request->ajax() && $user->id == Auth::id()) {
            try {
                if ($this->hasTooManySmsAttempts($user)) {
                    $minutes = $this->retrySmsAttemptInMinutes($user);

                    $response = __("Too many verification attempts! Retry in :minute minutes", [
                        'minute' => $minutes
                    ]);

                    return response($response, 403);
                }

                $user->notify(new PhoneVerification());

                $this->incrementSmsAttempts($user);

                return response(__("Confirmation code has been resent"));
            } catch (\Exception $e) {
                return response($e->getMessage(), 400);
            }
        } else {
            return abort(403);
        }
    }

    /**
     * Resend verification email
     *
     * @param Request $request
     * @param User $user
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\JsonResponse|\Illuminate\Http\Response
     */
    public function resendVerificationEmail(Request $request, User $user)
    {
        if ($request->ajax() && $user->id == Auth::id()) {
            $verification = resolve('Lunaweb\EmailVerification\EmailVerification');

            if ($this->hasTooManyEmailAttempts($user)) {
                $minutes = $this->retryEmailAttemptInMinutes($user);

                $response = __("Too many verification attempts! Retry in :minute minutes", [
                    'minute' => $minutes
                ]);

                return response($response, 403);
            }

            $verification->sendVerifyLink($user);

            $this->incrementEmailAttempts($user);

            return response(__("If you cannot find it in your inbox, check you spam box!"));

        } else {
            return abort(403);
        }
    }

    /**
     * @param Request $request
     * @param User $user
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function confirmPhone(Request $request, User $user)
    {
        if ($request->ajax() && $user->id == Auth::id()) {

            if ($user->token == $request->code) {

                if ($user->token_expiry < now()) {
                    return response(__("The token you entered has expired!"), 403);
                }

                $user->verified_phone = true;

                $user->save();

                return response(__("The operation was successful!"));
            }

            return response(__("The token you entered was invalid!"), 403);
        } else {
            return abort(403);
        }
    }

    /**
     * Set user's presence as online
     *
     * @param Request $request
     * @param User $user
     */
    public function setOnline(Request $request, User $user)
    {
        if ($request->ajax() && $user->id == Auth::id()) {
            $presence = $user->presence;

            $user->presence = "online";
            $user->last_seen = now();
            $user->save();

            if($presence != $user->presence){
                broadcast(new UserPresenceUpdated($user));
            }

        } else {
            return abort(403);
        }
    }

    /**
     * Set user's presence as away
     *
     * @param Request $request
     * @param User $user
     */
    public function setAway(Request $request, User $user)
    {
        if ($request->ajax() && $user->id == Auth::id()) {
            $presence = $user->presence;

            $user->presence = "away";
            $user->last_seen = now();
            $user->save();

            if($presence != $user->presence) {
                broadcast(new UserPresenceUpdated($user));
            }
        } else {
            return abort(403);
        }
    }

    /**
     * Get last ten notifications
     *
     * @param Request $request
     * @param User $user
     * @return
     */
    public function unreadNotifications(Request $request, $user)
    {
        if ($request->ajax()) {
            return $user->unreadNotifications()->latest()
                ->paginate(10, ['*'], 'page');
        } else {
            return abort(403);
        }
    }

    /**
     * Get notifications
     *
     * @param Request $request
     * @param User $user
     * @return
     */
    public function activeTradeChats(Request $request, $user)
    {
        if ($request->ajax()) {
            $chats = TradeChat::whereHas('trade', function ($query) use ($user) {
                $query->where(function ($query) use ($user) {
                    $query->where('partner_id', '=', $user->id);
                    $query->orWhere('user_id', '=', $user->id);
                })->whereIn('status', ['active', 'dispute']);
            })->where('user_id', '!=', $user->id)->with([
                'trade' => function ($query) {
                    $query->select(['id', 'token']);
                },
                'user' => function ($query) {
                    $query->select(['id', 'name', 'presence', 'last_seen']);
                },
                'user.profile' => function ($query) {
                    $query->select(['id', 'user_id', 'picture']);
                }
            ])->latest();

            return $chats->paginate(10, ['*'], 'page', $request->page ?: 0);
        } else {
            return abort(403);
        }
    }

    /**
     * @param Request $request
     * @param $user
     * @return mixed
     */
    public function getRatings(Request $request, $user)
    {
        $page = $request->page ?: 0;

        $records = $user->ratings()->has('user')
            ->with([
                'user' => function ($query) {
                    $query->select(['id', 'name', 'presence', 'last_seen']);
                },
                'user.profile' => function ($query) {
                    $query->select(['id', 'user_id', 'picture']);
                }
            ])->latest()->paginate(10, ['*'], 'page', $page);

        return $records;
    }
}
