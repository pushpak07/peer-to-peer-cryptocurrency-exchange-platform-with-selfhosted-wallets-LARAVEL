<?php

namespace App\Http\Controllers\Admin;

use App\Models\Offer;
use App\Models\Trade;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class HomeController extends Controller
{
    /**
     * Show admin dashboard
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return view('admin.home.index', [
            'escrow_wallet' => $this->getEscrowData(),
            'coins'         => get_coins(),
            'statistics'    => $this->getStatistics(),
        ]);
    }

    /**
     * Collect all statistical data
     *
     * @return \Illuminate\Support\Collection
     */
    private function getStatistics()
    {
        $statistics = collect([]);

        $statistics->put('users_count', $this->countUsers());
        $statistics->put('offers_count', $this->countOffers());
        $statistics->put('trades_count', $this->countTrades());
        $statistics->put('sum_revenue', $this->sumRevenue());

        return $statistics;

    }

    /**
     * Count all active offers
     *
     * @return int
     */
    private function countOffers()
    {
        return number_format(Offer::where('status', true)->count());
    }


    /**
     * Count all trades
     *
     * @return int
     */
    private function countTrades()
    {
        return number_format(Trade::whereNotIn('status', ['cancelled'])->count());
    }

    /**
     * Count all registered users
     *
     * @return int
     */
    private function countUsers()
    {
        return number_format(User::count());
    }

    /**
     * Get visible offers statistics
     *
     * @return array
     */
    public function visibleOffers()
    {
        $key = 'statistics.visible_offers';

        return Cache::remember($key, 30, function () {
            $statistics = [];

            $offers = Offer::has('user')->where('status', true)->get();

            $offers = $offers->filter(function ($offer) {
                return $offer->canShow();
            });

            $total = max(Offer::where('status', true)->count(), 1);

            $statistics['count'] = $offers->count();
            $percent = ($statistics['count'] * 100) / $total;
            $statistics['percent'] = round($percent);

            return $statistics;
        });
    }

    /**
     * Get completed trades statistics
     *
     * @return array
     */
    public function completedTrades()
    {
        $trades = DB::table('trades')->select(DB::raw('COUNT(*) as count, status'))
            ->groupBy('status')->get()->pluck('count', 'status');
        $statistics = array();

        $total = max(Trade::whereNotIn('status', ['cancelled'])->count(), 1);

        $statistics['dispute'] = isset($trades['dispute']) ? $trades['dispute'] : 0;
        $statistics['count'] = isset($trades['successful']) ? $trades['successful'] : 0;
        $statistics['percent'] = round(($statistics['count'] * 100) / $total);

        return $statistics;
    }

    /**
     * Get online users
     *
     * @return array
     */
    public function onlineUsers()
    {
        $total = max(User::count(), 1);
        $statistics = array();

        $statistics['count'] = User::whereNotIn('presence', ['offline'])->count();
        $statistics['percent'] = round(($statistics['count'] * 100) / $total);

        return $statistics;
    }

    /**
     * Sum all escrow revenue
     *
     * @return \Akaunting\Money\Money
     */
    private function sumRevenue()
    {
        $currency = config()->get('settings.default_currency');
        $revenue = 0;

        foreach (get_coins() as $key => $name) {
            $value = getEscrowWallet($key)->latest()->where('balance', '>', 0)
                ->sum('balance');

            $balance = coin($value, $key)->getValue();

            $revenue += get_price($balance, $key, $currency, false);
        }

        return money($revenue, $currency, true);
    }

    /**
     * Get wallet data
     *
     * @return array|null
     */
    private function getEscrowData()
    {
        $wallets = collect([]);

        $currency = config()->get('settings.default_currency');

        foreach (get_coins() as $key => $name) {
            $value = getEscrowWallet($key)
                ->latest()->where('balance', '>', 0)
                ->sum('balance');

            $balance = coin($value, $key)->getValue();

            $wallets->put($key, [
                'total' => $balance,
                'price' => get_price($balance, $key, $currency),
            ]);
        }


        return $wallets;
    }
}
