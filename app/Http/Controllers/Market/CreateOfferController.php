<?php

namespace App\Http\Controllers\Market;

use App\Models\Offer;
use App\Models\PaymentMethodCategory;
use Dirape\Token\Token;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

class CreateOfferController extends Controller
{
	/**
	 * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
	 */
    public function buyIndex()
    {
        return view('market.create_offer.buy', [
            'payment_methods' => $this->getPaymentMethods()
        ]);

    }

	/**
	 * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
	 */
    public function sellIndex()
    {
        return view('market.create_offer.sell', [
            'payment_methods' => $this->getPaymentMethods()
        ]);
    }

    /**
     * Store offer
     *
     * @param Request $request
     * @param $type
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request, $type)
    {
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

        if (in_array($type, ['buy', 'sell'])) {
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
                $offer = new Offer();

                $offer->fill($request->only([
                    'coin', 'payment_method', 'currency', 'label', 'trade_instruction',
                    'profit_margin', 'tags', 'min_amount', 'max_amount', 'deadline', 'terms',
                ]));

                $offer->fill([
                    'trusted_offer'      => $request->filled('trusted_offer'),
                    'phone_verification' => $request->filled('phone_verification'),
                    'email_verification' => $request->filled('email_verification'),
                    'type'               => $type,
                ]);

                if (!$offer->token) {
                    $offer->setToken();
                }
            } catch (\Exception $e) {
                return error_response($e->getMessage());
            }

            $user->offers()->save($offer);

            toastr()->success(__('Your offer has been created!'));

            return redirect()->route('home.index');

        } else {
            return abort(404);
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
}
