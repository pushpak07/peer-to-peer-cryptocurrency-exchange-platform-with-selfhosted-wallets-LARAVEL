<?php

namespace App\Http\Controllers\Resources;

use App\Models\Offer;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;

class OffersController extends Controller
{
    /**
     * Get data of buy offers
     *
     * @param Request $request
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function buy(Request $request)
    {
        $offers = Cache::remember("offers.buy", 30, function () {
            return Offer::has('user')
                ->where('type', 'buy')->where('status', true)
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

        if ($filter = $request->coin) {
            $offers = $offers->where('coin', $filter);
        }

        if ($filter = $request->amount) {
            $offers = $offers->where('min_amount', '<=', $filter)->where('max_amount', '>=', $filter);
        }

        if ($filter = $request->payment_method) {
            $offers = $offers->where('payment_method', $filter);
        }

        $offers = $offers->filter(function ($offer) {
            return $offer->canShow();
        });

        return paginate($offers, 100);
    }

    /**
     * Get data of sell offers
     *
     * @param Request $request
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function sell(Request $request)
    {
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

        if ($filter = $request->coin) {
            $offers = $offers->where('coin', $filter);
        }

        if ($filter = $request->amount) {
            $offers = $offers->where('min_amount', '<=', $filter)->where('max_amount', '>=', $filter);
        }

        if ($filter = $request->payment_method) {
            $offers = $offers->where('payment_method', $filter);
        }

        $offers = $offers->filter(function ($offer) {
            return $offer->canShow();
        });

        return paginate($offers, 100);
    }

    /**
     * Get offer by id
     *
     * @param $id
     * @return Offer|Offer[]
     */
    public function get($id)
    {
        return Offer::has('user')
            ->where('status', true)->where('type', 'buy')
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
            ])
            ->findOrFail($id);
    }
}
