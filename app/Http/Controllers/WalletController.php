<?php

namespace App\Http\Controllers;

use App\Models\BitcoinWallet;
use App\Models\DashWallet;
use App\Models\LitecoinWallet;
use App\Models\User;
use Carbon\Carbon;
use Cryptocompare\Price;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use PragmaRX\Google2FA\Google2FA;
use Yajra\DataTables\Facades\DataTables;

class WalletController extends Controller
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
        return view('wallet.index');
    }

    /**
     * @param Request $request
     * @param string $coin
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function generateAddress(Request $request, $coin = 'btc')
    {
        if ($request->ajax()) {
            if ($wallet = Auth::user()->getCoinWallet($coin)->first()) {

                $adapter = getBlockchainAdapter($coin);

                if ($wallet->transactions()->count()) {

                    $address = $adapter->createWalletAddress($wallet);

                    $wallet->addresses()->create(['address' => $address['address']]);

                    $message = __('A new address has been created successfully!');

                    return success_response($message);
                }

            } else {
                $this->createWallet(Auth::user(), $coin);
            }

        } else {
            return abort(403);
        }
    }

    /**
     * @param User $user
     * @param $coin
     * @return mixed
     * @throws \Exception
     */
    private function createWallet(User $user, $coin)
    {
        $label = $user->name;
        $passphrase = str_random(10);

        $adapter = getBlockchainAdapter($coin);

        $data = $adapter->generateWallet($label, $passphrase);

        $wallet = $user->getCoinWallet($coin)->create([
            'wallet_id' => $data['id'],
            'keys' => $data['keys'],
            'balance' => $data['confirmedBalance'],
            'passphrase' => $passphrase,
            'label' => $data['label'],
        ]);

        $address = $data['receiveAddress'];

        $wallet->addresses()->create([
            'address' => $address['address']
        ]);

        return $wallet;
    }

    /**
     * @param Request $request
     * @param string $coin
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function send(Request $request, $coin = 'btc')
    {
        $wallet = Auth::user()->getCoinWallet($coin)->first();

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

            'amount' => [
                'required', 'numeric',
                function ($attribute, $value, $fail) use ($coin) {
                    if ($value > 0 && Auth::user()->getCoinAvailable($coin) < $value) {
                        $fail(__('Your current balance is insufficient!'));
                        return;
                    }

                    if ($value == 0) {
                        $fail(__('Invalid amount entered! Please verify and try again.'));
                        return;
                    }

                    if ($value < 0 && Auth::user()->activeTrades($coin)->count()) {
                        $fail(__('You cannot clear your balance with active trades!'));
                        return;
                    }
                }
            ],

            'address' => [
                'required',
                function ($attribute, $value, $fail) use ($wallet) {
                    $addresses = $wallet->addresses()->get();

                    if ($addresses->contains('address', $value)) {
                        $fail(__('You cannot send to yourself!'));
                    }
                }
            ]
        ]);

        try {
            $adapter = getBlockchainAdapter($coin);

            if ($request->amount > 0) {
                $amount = coin($request->amount, $coin, true)
                    ->getAmount();

                $adapter->send($wallet, $request->address, (int) $amount);
            } else {
                $adapter->send($wallet, $request->address, -1);
            }

        } catch (\Exception $e) {
            return error_response($e->getMessage());
        }

        $message = __('Your transaction was successful!');

        return success_response($message);
    }

    /**
     * Get address data
     *
     * @param Request $request
     * @param string $coin
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function addressData(Request $request, $coin = 'btc')
    {
        if ($request->ajax()) {
            $addresses = Auth::user()->getAddressModel($coin)
                ->latest()->get();

            return DataTables::of($addresses)
                ->editColumn('created_at', function ($data) {
                    if ($date = Carbon::parse($data->created_at)) {

                        return $date->timezone(Auth::user()->timezone)
                            ->toDayDateTimeString();

                    }
                })
                ->make(true);
        } else {
            return abort(403);
        }
    }

    /**
     * @param Request $request
     * @param string $coin
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function transactionData(Request $request, $coin = 'btc')
    {
        if ($request->ajax()) {
            $transactions = Auth::user()->getTransactionModel($coin)
                ->latest()->get();

            return DataTables::of($transactions)
                ->addColumn('type', function ($data) {
                    if ($data->type == 'receive') {
                        return __("Incoming");
                    } else {
                        return __("Outgoing");
                    }
                })
                ->editColumn('date', function ($data) {
                    if ($date = Carbon::parse($data->date)) {

                        return $date->timezone(Auth::user()->timezone)
                            ->toDayDateTimeString();

                    }
                })
                ->editColumn('value', function ($data) use ($coin) {
                    return coin($data->value, $coin)->getValue();
                })
                ->make(true);
        } else {
            return abort(403);
        }
    }
}
