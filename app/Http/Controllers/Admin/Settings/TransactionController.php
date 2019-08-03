<?php

namespace App\Http\Controllers\Admin\Settings;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Validation\Rule;
use Jackiedo\DotenvEditor\DotenvEditor;

class TransactionController extends Controller
{

    /**
     * @var DotenvEditor
     */
    protected $editor;

    /**
     * NotificationController constructor.
     *
     * @param DotenvEditor $editor
     */
    public function __construct(DotenvEditor $editor)
    {
        $this->editor = $editor;
    }


    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return view('admin.settings.transaction.index');
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
            'SET_DEFAULT_CURRENCY' => [
                'rules' => [
                    'required', Rule::in(array_keys(get_iso_currencies()))
                ],
            ],

            'SET_MIN_TX_CONFIRMATIONS' => [
                'rules' => 'required|numeric|min:1',
            ],

            'SET_TX_NUM_BLOCKS' => [
                'rules' => 'required|numeric|min:1|max:7',
            ],

            'SET_MIN_OFFER_AMOUNT' => [
                'rules' => 'required|numeric|min:1',
            ],

            'SET_MAX_OFFER_AMOUNT' => [
                'rules' => 'required|numeric|gt:SET_MIN_OFFER_AMOUNT',
            ],

            'SET_BTC_PROFIT_PER_WALLET_LIMIT' => [
                'rules' => 'required|numeric|min:10',
            ],

            'SET_BTC_LOCKED_BALANCE' => [
                'rules' => 'required|numeric|min:0',
            ],

            'SET_LTC_PROFIT_PER_WALLET_LIMIT' => [
                'rules' => 'required|numeric|min:10',
            ],

            'SET_LTC_LOCKED_BALANCE' => [
                'rules' => 'required|numeric|min:0',
            ],

            'SET_DASH_PROFIT_PER_WALLET_LIMIT' => [
                'rules' => 'required|numeric|min:10',
            ],

            'SET_DASH_LOCKED_BALANCE' => [
                'rules' => 'required|numeric|min:0',
            ],

        ];
    }
}
