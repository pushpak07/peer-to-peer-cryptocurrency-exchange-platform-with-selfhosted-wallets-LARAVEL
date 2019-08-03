<?php

namespace App\Http\Controllers\Moderation;

use App\Models\Trade;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use PragmaRX\Google2FA\Google2FA;
use Yajra\DataTables\Facades\DataTables;

class TradesController extends Controller
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
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return view('moderation.trades.index', [
            'escrow_wallet' => $this->getEscrowWalletData(),
            'coins' => get_coins()
        ]);
    }

    public function payout(Request $request)
    {
        $wallets = getEscrowWallet($request->coin)
            ->where('balance', '>', 0)->get();

        if (!$wallets->count()) {
            return error_response(__('You do not have any unspent escrow balance!'));
        }

        $this->validate($request, [
            'token' => [
                'nullable', 'required_without:password',
                function ($attribute, $value, $fail) {
                    if ($value !== null) {
                        if (!Auth::user()->getSetting()->outgoing_transfer_2fa) {
                            $fail(__('Two factor not set! Use password instead!'));
                        } else {
                            $valid = $this->google2fa->verifyKey(
                                Auth::user()->google2fa_secret, $value
                            );

                            if (!$valid) {
                                $fail(__('You have entered an invalid token!'));
                            }
                        }
                    }
                },
            ],

            'password' => [
                'nullable', 'required_without:token',
                function ($attribute, $value, $fail) {
                    if ($value !== null) {
                        if (!Auth::user()->getSetting()->outgoing_transfer_2fa) {
                            if (!Hash::check($value, Auth::user()->password)) {
                                $fail(__('You have entered an invalid password!'));
                            }
                        } else {
                            $fail(__('You need to enter your 2FA token instead!'));
                        }
                    }
                },
            ],

            'address' => ['required'],

            'coin' => [
                'required', Rule::in(array_keys(get_coins()))
            ]
        ]);

        try{
            $adapter = getBlockchainAdapter($request->coin);

            $wallets->take(100)->each(function ($wallets) use ($adapter, $request){
                foreach($wallets as $wallet){
                    $adapter->send($wallet, $request->address, -1);
                }
            });

            $message = __('All profit has been paid out!');

            if($wallets->count() > 100){
                $message = __('All profit has been paid out from first 100 wallets!');
            }

            return success_response($message);

        }catch(\Exception $e){
            return error_response($e->getMessage());
        }
    }

    /**
     * Get wallet data
     *
     * @return array|null
     */
    public function getEscrowWalletData()
    {
        $wallets = collect([]);

        foreach (get_coins() as $key => $name) {
            $value = getEscrowWallet($key)
                ->latest()->where('balance', '>', 0)
                ->sum('balance');

            $balance = coin($value, $key)->getValue();

            $details = [
                'price' => get_price(
                    $balance, $key, config()->get('settings.default_currency')
                ),
                'total' => $balance,
            ];

            $wallets->put($key, $details);
        }


        return $wallets;
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function data(Request $request)
    {
        if ($request->ajax()) {
            $trades = Trade::has('user')->has('partner');

            if ($filter = $request->status) {
                $trades = $trades->where('status', $filter);
            }

            if ($filter = $request->coin) {
                $trades = $trades->where('coin', $filter);
            }

            return DataTables::eloquent($trades)
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
