<?php

namespace App\Http\Controllers\Market;

use App\Models\Offer;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Yajra\DataTables\Facades\DataTables;

class BuyCoinController extends Controller
{
    /**
     * Show sell offers
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return view('market.buy_coin.index', [
            'coins' => get_coins(),
        ]);
    }

    public function data(Request $request)
    {
        if ($request->ajax()) {
            $offers = Cache::remember("offers.sell", 30, function () {
                return Offer::has('user')
                    ->where('type', 'sell')->where('status', true)
                    ->with([
                        'user'         => function ($query) {
                            $query->select([
                                'id', 'name', 'presence', 'last_seen', 'currency', 'status', 'timezone',
                                'verified_phone', 'verified', 'schedule_delete', 'schedule_deactivate'
                            ]);
                        },
                        'user.profile' => function ($query) {
                            $query->select([
                                'id', 'user_id', 'picture', 'first_name', 'last_name', 'bio'
                            ]);
                        }
                    ])->get();
            });

            if ($filter = $request->currency) {
                $offers = $offers->where('currency', $filter);
            }

            if ($filter = $request->amount) {
                $offers = $offers->where('min_amount', '<=', $filter)->where('max_amount', '>=', $filter);
            }

            if ($filter = $request->coin) {
                $offers = $offers->where('coin', $filter);
            }

            if ($filter = $request->payment_method) {
                $offers = $offers->where('payment_method', $filter);
            }

            $offers = $offers->filter(function ($offer) {
                return $offer->canShow(Auth::user(), true);
            });

            return DataTables::of($offers)
                ->addColumn('seller', function ($data) {
                    return view('market.buy_coin.partials.datatable.seller')
                        ->with(compact('data'));
                })
                ->editColumn('coin', function ($data) {
                    return get_coin($data->coin);
                })
                ->editColumn('payment_method', function ($data) {
                    return view('market.buy_coin.partials.datatable.payment_method')
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
                ->addColumn('action', function ($data) {
                    return view('market.buy_coin.partials.datatable.action')
                        ->with(compact('data'));
                })
                ->rawColumns(['coin_rate', 'action', 'amount_range', 'payment_method', 'seller'])
                ->removeColumn('user_id', 'trusted_offer')
                ->make(true);
        } else {
            return abort(404);
        }
    }
}
