<?php
/**
 * ======================================================================================================
 * File Name: BitcoinAdapter.php
 * ======================================================================================================
 * Author: HolluwaTosin360
 * ------------------------------------------------------------------------------------------------------
 * Portfolio: http://codecanyon.net/user/holluwatosin360
 * ------------------------------------------------------------------------------------------------------
 * Date & Time: 9/29/2018 (2:08 PM)
 * ------------------------------------------------------------------------------------------------------
 *
 * Copyright (c) 2018. This project is released under the standard of CodeCanyon License.
 * You may NOT modify/redistribute this copy of the project. We reserve the right to take legal actions
 * if any part of the license is violated. Learn more: https://codecanyon.net/licenses/standard.
 *
 * ------------------------------------------------------------------------------------------------------
 */

namespace App\Logics\Adapters;


use App\Logics\Adapters\Traits\Adapter;
use App\Logics\Services\BlockCypher;
use App\Models\BitcoinAddress;
use App\Models\BitcoinTransaction;
use App\Models\BitcoinWallet;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\URL;
use neto737\BitGoSDK\BitGoExpress;
use neto737\BitGoSDK\BitGoSDK;
use neto737\BitGoSDK\Enum\CurrencyCode;

class BitcoinAdapter
{
    use Adapter;

    /**
     * Default wallet name
     *
     * @var string
     */
    protected $coin;

    /**
     * BitGoExpress instance
     *
     * @var BitGoExpress
     */
    public $express;

    /**
     * BitcoinAdapter constructor.
     *
     * @throws \Exception
     */
    public function __construct()
    {
        $config = config()->get('services.bitgo');

        $this->express = new BitGoExpress(
            $config['host'], $config['port'], ($config['env'] === 'prod') ? 'btc' : 'tbtc'
        );

        $this->express->accessToken = $config['token'];
    }

    /**
     * Update input balance
     *
     * @param BitcoinWallet $wallet
     * @param $transfer
     */
    public function updateInputBalance($wallet, $transfer)
    {
        $wallet->decrement('balance', abs($transfer['value']));
    }

    /**
     * Update output balance
     *
     * @param $output
     * @param int $amount
     */
    public function updateOutputBalance($output, $amount = 0)
    {

	    if (!is_array($output)) {
		    $address = BitcoinAddress::where('address', $output)->first();

		    if ($address) {
			    $address->wallet->increment('balance', $amount);
		    }
	    } else {
		    foreach ($output as $out) {
			    $address = BitcoinAddress::where('address', $out['address'])->first();

			    if ($address) {
				    $address->wallet->increment('balance', $out['amount']);
			    }
		    }
	    }
    }

    /**
     * @param $id
     * @return string
     */
    private function getWebhookUrl()
    {
        $base_url = request()->getBaseUrl();

        //TODO: Test Purpose. Please Remove before production
        if (app()->environment() === 'local') {
            URL::forceRootUrl(config('app.url'));
        }

        $webhook = route('bitgo.hook.btc');

        //TODO: Test Purpose. Please Remove before production
        if (app()->environment() === 'local') {
            URL::forceRootUrl($base_url);
        }

        return $webhook;
    }
}
