<?php

namespace App\Http\Controllers\Profile;

use App\Models\Trade;
use App\Models\User;
use App\Notifications\Authentication\UserDeactivated;
use App\Notifications\Authentication\UserRegistered;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class ProfileController extends Controller
{
    /**
     * Show Profile Index
     *
     * @param User $user
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(User $user)
    {
        return view('profile.index')
            ->with(compact('user'));
    }

    /**
     * @param Request $request
     * @param User $user
     * @throws \Exception
     */
    public function offersData(Request $request, User $user)
    {
        if ($request->ajax()) {
            $offers = $user->offers()
                ->where('status', true)
                ->get();

            $offers = $offers->filter(function ($offer, $key) use ($user) {
                if (!$offer->trust(Auth::user())) return false;

                if ($offer->type == 'sell') {
                    $balance = $user->getCoinAvailable($offer->coin);

                    $available = get_price(
                        $balance, $offer->coin, $offer->currency, false
                    );

                    $fee = calc_fee($offer->max_amount, $offer->coin);

                    return ($offer->max_amount + $fee) <= $available;
                }

                return true;
            });

            return DataTables::of($offers)
                ->addColumn('action', function ($data) {
                    return view('profile.partials.datatable.action')
                        ->with(compact('data'));
                })
                ->editColumn('coin', function ($data) {
                    return get_coin($data->coin);
                })
                ->editColumn('payment_method', function ($data) {
                    return view('profile.partials.datatable.payment_method')
                        ->with(compact('data'));
                })
                ->addColumn('amount_range', function ($data) {
                    $min = money($data->min_amount, $data->currency, true);
                    $max = money($data->max_amount, $data->currency, true);

                    return "<b>{$min}</b>" . ' - ' . "<b>{$max}</b>";
                })
                ->addColumn('worth', function ($data) {
                    return (100 - $data->profit_margin) . '%';
                })
                ->addColumn('coin_rate', function ($data) {
                    return get_price(
                        $data->multiplier(), $data->coin, $data->currency
                    );
                })
                ->rawColumns(['coin_rate', 'action', 'amount_range', 'payment_method'])
                ->removeColumn('user_id', 'trusted_offer')
                ->make(true);
        } else {
            return abort(404);
        }
    }

    public function ratingsData(Request $request, User $user)
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

    /**
     * @param Request $request
     * @param User $user
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function deactivate(Request $request, User $user)
    {
        if ($request->ajax()) {
            $trades = Trade::whereIn('status', ['active', 'dispute'])
                ->where(function ($query) use ($user) {
                    $query->where('partner_id', $user->id)
                        ->orWhere('user_id', $user->id);
                });

            $user->moderation_activities()->create([
                'activity'  => 'Deactivated User',
                'moderator' => Auth::user()->name,
                'comment'   => $request->prompt
            ]);

            if ($trades->count()) {
                $user->schedule_deactivate = true;
                $user->save();

                $message = __("The user has been scheduled for deactivation!");

                return response($message);
            }

            $user->status = 'inactive';
            $user->save();

            $user->notify(new UserDeactivated());

            return response(__("The user has been deactivated successfully!"));
        }else{
            return abort(403);
        }
    }
}
