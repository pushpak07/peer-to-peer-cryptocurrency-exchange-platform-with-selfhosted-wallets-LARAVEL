<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use Jackiedo\DotenvEditor\DotenvEditor;
use PragmaRX\Google2FA\Google2FA;
use Yajra\DataTables\Facades\DataTables;

class EarningsController extends Controller
{

    /**
     * @var DotenvEditor
     */
    protected $editor;

    /**
     * An instance of google 2fa generator
     *
     * @var Google2FA
     */
    protected $google2fa;

    /**
     * Create a new controller instance.
     *
     * @param DotenvEditor $editor
     * @param Google2FA $google2fa
     * @return void
     */
    public function __construct(DotenvEditor $editor, Google2FA $google2fa)
    {
        $this->google2fa = $google2fa;
        $this->editor = $editor;
    }

    /**
     * Show earnings page
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return view('admin.earnings.index', [
            'escrow_wallet' => $this->getEscrowData(),
        ]);
    }

    /**
     * Process earnings payout
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function payout(Request $request)
    {
        $wallet = getEscrowWallet($request->coin)->find($request->id);

        if (!$wallet) {
            return error_response(__('Selected escrow wallet could not be found!'));
        }

        if (!$wallet->balance > 0) {
            return error_response(__('Selected escrow wallet does not have sufficient balance!'));
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

        try {
            $adapter = getBlockchainAdapter($request->coin);

            $adapter->send($wallet, $request->address, -1);

        } catch (\Exception $e) {
            return error_response($e->getMessage());
        }

        $message = __('Balance was paid out successfully!');

        return success_response($message);
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

    public function data(Request $request, $coin)
    {
        if ($request->ajax()) {
            $wallets = getEscrowWallet($coin);

            return DataTables::of($wallets->get())
                ->addColumn('address', function ($data) {
                    if ($address = $data->addresses()->first()) {
                        return $address->address;
                    }
                })
                ->editColumn('balance', function ($data) use ($coin) {
                    return coin($data->balance, $coin)->getValue();
                })
                ->addColumn('action', function ($data) use ($coin) {
                    return view('admin.earnings.partials.datatable.action')
                        ->with(compact('data', 'coin'));
                })
                ->removeColumn('passphrase', 'keys')
                ->make(true);

        } else {
            return abort(404);
        }
    }

    /**
     * Update general configurations
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function update(Request $request)
    {
        $environment = collect($this->environment())
            ->intersectByKeys($request->all());

        $rules = $environment->mapWithKeys(function ($value, $key) {
            return [$key => $value['rules']];
        });

        $this->validate($request, $rules->toArray());

        $values = $environment->map(function ($value, $key) use ($request) {
            $data = [
                'key'   => $key,
                'value' => $request->get($key)
            ];

            if (isset($value['save'])) {
                $data = [
                    'key'   => $key,
                    'value' => $value['save']($request)
                ];
            }

            return $data;
        });

        $this->editor->setKeys($values->toArray());
        $this->editor->save();

        $message = __("Your settings has been updated!");

        return success_response($message);
    }

    /**
     * Define environment properties
     *
     * @return array
     */
    private function environment()
    {
        return [
            'SET_BTC_TRADE_FEE' => [
                'rules' => 'required|numeric|min:0',
            ],

            'SET_LTC_TRADE_FEE' => [
                'rules' => 'required|numeric|min:0',
            ],

            'SET_DASH_TRADE_FEE' => [
                'rules' => 'required|numeric|min:0',
            ],
        ];
    }
}
