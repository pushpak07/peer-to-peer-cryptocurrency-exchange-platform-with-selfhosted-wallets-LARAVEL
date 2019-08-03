<?php

namespace App\Http\Controllers\Home;

use App\Models\Offer;
use App\Models\PaymentMethodCategory;
use App\Models\Trade;
use App\Notifications\Trades\Started;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Yajra\DataTables\Facades\DataTables;

class OffersController extends Controller
{
    /**
     * @param Request $request
     * @param $token
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index($token)
    {
        $offers = Offer::has('user')->where('status', true)
            ->where('token', $token)->get();

        $offers = $offers->filter(function ($offer) {return $offer->canShow(Auth::user());});

        if ($offer = $offers->first()) {
            $rate = get_price($offer->multiplier(), $offer->coin, $offer->currency, false);
            $rate_formatted = get_price($offer->multiplier(), $offer->coin, $offer->currency);

            $min_amount = money($offer->min_amount, $offer->currency, true);
            $max_amount = money($offer->max_amount, $offer->currency, true);

            return view('home.offers.index')
                ->with(compact('min_amount', 'max_amount'))
                ->with(compact('offer', 'rate', 'rate_formatted'));
        } else {
            return abort(404);
        }
    }

	/**
	 * @param $token
	 * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
	 */
	public function edit($token)
	{
		$offer = Auth::user()->offers()
			->where('token', $token)->first();

		if(!$offer){
			toastr()->error(__('Offer could not be found!'));

			return redirect()->route('home.index');
		}

		$payment_methods = $this->getPaymentMethods();

		return view('home.offers.edit')
			->with(compact('offer'))
			->with(compact('payment_methods'));
	}


	/**
	 * update offer
	 *
	 * @param Request $request
	 * @param $token
	 * @return \Illuminate\Http\RedirectResponse
	 */
	public function update(Request $request, $token)
	{
		$offer = Auth::user()->offers()
			->where('token', $token)->first();

		if($currency = $request->currency){
			$min_offer_amount = currency_convert(
				(float) config('settings.min_offer_amount'), 'USD', $currency
			);

			$min_amount_rule = "required|numeric|min:{$min_offer_amount}";

			$max_offer_amount = currency_convert(
				(float) config('settings.max_offer_amount'), 'USD', $currency
			);

			$max_amount_rule = "required|numeric|max:{$max_offer_amount}|gte:min_amount";
		}else{
			$min_amount_rule = 'required|numeric|min:0';
			$max_amount_rule = 'required|numeric|min:0|gte:min_amount';
		}

		$payment_methods = collect($this->getPaymentMethods());
		$coins = collect(get_coins());
		$currencies = collect(get_iso_currencies());

		if ($offer) {
			$user = Auth::user();

			$this->validate($request, [
				'min_amount'        => $min_amount_rule,
				'max_amount'        => $max_amount_rule,

				'payment_method'    => ['required', Rule::in($payment_methods->flatten())],
				'currency'          => ['required', Rule::in($currencies->keys()->toArray())],
				'coin'              => ['required', Rule::in($coins->keys()->toArray())],

				'tags'              => 'required|array|max:3',

				'label'             => 'required|string|max:25',
				'terms'             => 'required|string',
				'trade_instruction' => 'required|string',

				'deadline'          => 'required|numeric|min:0',
				'profit_margin'     => 'required|numeric',
			]);

			try {
				$offer->fill($request->only([
					'coin', 'payment_method', 'currency', 'label', 'trade_instruction',
					'profit_margin', 'tags', 'min_amount', 'max_amount', 'deadline', 'terms',
				]));

				$offer->fill([
					'trusted_offer'      => $request->filled('trusted_offer'),
					'phone_verification' => $request->filled('phone_verification'),
					'email_verification' => $request->filled('email_verification'),
				]);
			} catch (\Exception $e) {
				return error_response($e->getMessage());
			}

			$user->offers()->save($offer);

			toastr()->success(__('Your offer has been updated!'));

			return redirect()->route('home.index');

		} else {
			toastr()->error(__('Offer could not be found!'));

			return redirect()->route('home.index');
		}
	}

	/**
	 * @return array
	 */
	public function getPaymentMethods()
	{
		$categories = PaymentMethodCategory::all();

		$payment_methods = array();

		foreach ($categories as $category) {
			$payment_methods[$category->name] = $category->payment_methods()
				->get()->pluck('name', 'name');
		}

		return $payment_methods;
	}

    /**
     * @param Request $request
     * @param $token
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     * @throws \Throwable
     */
    public function startTrade(Request $request, $token)
    {
        $offers = Offer::has('user')->where('status', true)
            ->where('token', $token)->get();

        $offers = $offers->filter(function ($offer) {
            if (!$offer->canShow(Auth::user(), true)) return false;

            if (!$offer->canTradeWith(Auth::user())) return false;

            return true;
        });

        if ($offer = $offers->first()) {

            $this->validate($request, [
                'amount' => [
                    'required', 'numeric',
                    'min:' . $offer->min_amount,
                    'max:' . $offer->max_amount,
                    function ($attribute, $value, $fail) use ($offer, $request) {
                        if ($offer->type == 'buy') {
                            $balance = Auth::user()->getCoinAvailable($offer->coin);

                            $available = get_price(
                                $balance, $offer->coin, $offer->currency, false
                            );

                            $fee = calc_fee($value, $offer->coin);

                            if (($value + $fee) > $available) {
                                $fail(__('Your current wallet balance is not enough.'));
                            }
                        } else {
                            // Verify if user has an address to receive the coin
                            if (!Auth::user()->getAddressModel($offer->coin)->count()) {
                                $fail(__('You do not have a :coin address yet.', [
                                    'coin' => get_coin($offer->coin)
                                ]));
                            }
                        }
                    },
                ]
            ]);

            $trade = new Trade();

            $rate = get_price(
                $offer->multiplier(), $offer->coin, $offer->currency, false
            );

            $trade->type = ($offer->type == 'sell') ? 'buy' : 'sell';

            try {

                $trade->fill([
                    'coin' => $offer->coin,
                    'partner_id' => $offer->user->id,
                    'offer_id' => $offer->id,
                    'currency' => $offer->currency,
                    'fee' => get_fee_percentage($offer->coin),
                    'offer_terms' => $offer->terms,
                    'instruction' => $offer->trade_instruction,
                    'label' => $offer->label,
                    'payment_method' => $offer->payment_method,
                    'deadline' => $offer->deadline,
                    'amount' => $request->amount,
                    'rate' => $rate
                ]);

                if (!$trade->token) $trade->setToken();

                $trade = Auth::user()->trades()->save($trade);
                $trade->partner->notify(new Started($trade));

                return redirect()->route('home.trades.index', [
                    'token' => $trade->token
                ]);
            } catch (\Exception $e) {
                return error_response($e->getMessage());
            }
        } else {
            return abort(404);
        }
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function data(Request $request)
    {
        if ($request->ajax()) {
            $offers = Auth::user()->offers()->get();

            return DataTables::of($offers)
                ->addColumn('action', function ($data) {
                    return view('home.offers.partials.datatable.action')
                        ->with(compact('data'));
                })
                ->editColumn('coin', function ($data) {
                    return get_coin($data->coin);
                })
                ->editColumn('status', function ($data) {
                    if ($data->status) {
                        return "<span class='btn btn-icon btn-sm btn-pure darken-4 success'><i class='ft-eye'></i></span>";
                    } else {
                        return "<span class='btn btn-icon btn-sm btn-pure darken-4 danger'><i class='ft-eye-off'></i></span>";
                    }
                })
                ->editColumn('profit_margin', function ($data) {
                    return $data->profit_margin . '%';
                })
                ->addColumn('amount_range', function ($data) {
                    $min = money($data->min_amount, $data->currency, true);
                    $max = money($data->max_amount, $data->currency, true);

                    return "<b>{$min}</b>" . ' - ' . "<b>{$max}</b>";
                })
                ->editColumn('type', function ($data) {
                    return strtoupper($data->type);
                })
                ->rawColumns(['action', 'status', 'payment_method', 'amount_range'])
                ->make(true);
        } else {
            return abort(404);
        }
    }

    /**
     * @param Request $request
     * @param $token
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function toggle(Request $request, $token)
    {
        if ($request->ajax()) {
            if ($offer = Auth::user()->offers()->where('token', $token)->first()) {
                $offer->status = !$offer->status;

                $offer->save();

                return response(__("Your offer has been updated!"));
            } else {
                $message = __('The offer could not be found!');

                return response($message, 404);
            }
        } else {
            return abort(404);
        }
    }

    /**
     * Delete user's offer
     *
     * @param Request $request
     * @param $token
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function delete(Request $request, $token)
    {
        if ($request->ajax()) {
            $offers = Auth::user()->offers()->where('token', $token);

            if ($offer = $offers->first()) {
                try {
                    $offer->delete();

                    return response(__("Offer has been removed!"));

                } catch (\Exception $e) {
                    return response($e->getMessage(), 404);
                }
            } else {
                $message = __('The offer could not be found!');

                return response($message, 404);
            }
        } else {
            return abort(404);
        }
    }

}
