<?php

namespace App\Http\Controllers\Profile;

use App\Events\NotificationsUpdated;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class NotificationsController extends Controller
{

    /**
     * Show Profile Notifications
     *
     * @param User $user
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(User $user)
    {
        $notifications = $user->notifications()->paginate(15);

        return view('profile.notifications.index')
            ->with(compact('user', 'notifications'));
    }

    /**
     * Mark all unreadNotifications as read
     *
     * @param User $user
     * @return \Illuminate\Http\RedirectResponse
     */
    public function markAllAsRead(User $user)
    {
        $user->unreadNotifications()->update(['read_at' => now()]);

        broadcast(new NotificationsUpdated($user));

        toastr()->success(__("Your request was successful!"));

        return redirect()->back();
    }

    /**
     * @param Request $request
     * @param User $user
     * @param $id
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function markAsRead(Request $request, User $user, $id)
    {
        if($request->ajax()){
            $notification = $user->notifications()
                ->find($id);

            if($notification){
                $notification->markAsRead();
            }

            return response('Success', 200);
        }else{
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
    public function data(Request $request, User $user)
    {
        if ($request->ajax()) {
            return $user->notifications()->latest()
                ->paginate(10, ['*'], 'page', $request->page ?: 0);
        } else {
            return abort(403);
        }
    }
}
