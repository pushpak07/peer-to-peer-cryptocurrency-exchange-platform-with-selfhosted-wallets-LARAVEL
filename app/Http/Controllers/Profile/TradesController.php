<?php

namespace App\Http\Controllers\Profile;

use App\Models\Trade;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class TradesController extends Controller
{
    /**
     * Show Profile Trades
     *
     * @param User $user
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(User $user)
    {
        return view('profile.trades.index')
            ->with(compact('user'));
    }

    /**
     * @param Request $request
     * @throws \Exception
     */
    public function data(Request $request, User $user)
    {
        if ($request->ajax()) {
            $trades = Trade::has('user')->has('partner')
                ->where(function ($query) use ($user) {
                    $query->where('partner_id', '=', $user->id);
                    $query->orWhere('user_id', '=', $user->id);
                });

            if ($filter = $request->status) {
                $trades = $trades->where('status', $filter);
            }

            $trades = $trades->get();

            $trades = $trades->filter(function ($trade) use ($request, $user) {
                if ($request->type == 'buy') {
                    return $trade->buyer()->id == $user->id;
                }

                if ($request->type == 'sell') {
                    return $trade->seller()->id == $user->id;
                }

                return true;
            });

            return DataTables::of($trades)
                ->editColumn('type', function ($data) {
                    return strtoupper($data->type);
                })
                ->editColumn('status', function ($data) {
                    $status = ucfirst($data->status);

                    switch ($data->status) {
                        case 'active':
                            $html = "<span class='badge badge-info'>{$status}</span>";
                            break;
                        case 'successful':
                            $html = "<span class='badge badge-success'>{$status}</span>";
                            break;
                        case 'cancelled':
                            $html = "<span class='badge badge-danger'>{$status}</span>";
                            break;
                        case 'dispute':
                            $html = "<span class='badge badge-warning'>{$status}</span>";
                            break;
                        default:
                            $html = "<span class='badge badge-secondary'>{$status}</span>";
                    }

                    return $html;
                })
                ->editColumn('coin', function ($data) {
                    return get_coin($data->coin);
                })
                ->editColumn('amount', function ($data) {
                    return money($data->amount, $data->currency, true);
                })
                ->editColumn('rate', function ($data) {
                    return money($data->rate, $data->currency, true);
                })
                ->addColumn('coin_value', function ($data) {
                    return $data->coinValue() . strtoupper($data->coin);
                })
                ->addColumn('buyer', function ($data) {
                    return view('home.trades.partials.datatable.buyer')
                        ->with(compact('data'));
                })
                ->addColumn('seller', function ($data) {
                    return view('home.trades.partials.datatable.seller')
                        ->with(compact('data'));
                })
                ->addColumn('trade', function ($data) {
                    return \HTML::link(route('home.trades.index', [
                        'token' => $data->token
                    ]), $data->token);
                })
                ->addColumn('offer', function ($data) {
                    if ($offer = $data->offer) {
                        return \HTML::link(route('home.offers.index', [
                            'token' => $offer->token
                        ]), $offer->token);
                    }
                })
                ->removeColumn('dispute_by', 'dispute_comment')
                ->rawColumns(['status', 'buyer', 'seller'])
                ->make(true);
        } else {
            return abort(404);
        }

    }
}
