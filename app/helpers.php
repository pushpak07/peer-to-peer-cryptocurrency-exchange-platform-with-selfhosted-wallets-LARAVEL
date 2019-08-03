<?php
/**
 * ======================================================================================================
 * File Name: helpers.php
 * ======================================================================================================
 * Author: HolluwaTosin360
 * ------------------------------------------------------------------------------------------------------
 * Portfolio: http://codecanyon.net/user/holluwatosin360
 * ------------------------------------------------------------------------------------------------------
 * Date & Time: 8/21/2018 (6:41 PM)
 * ------------------------------------------------------------------------------------------------------
 *
 * Copyright (c) 2018. This project is released under the standard of CodeCanyon License.
 * You may NOT modify/redistribute this copy of the project. We reserve the right to take legal actions
 * if any part of the license is violated. Learn more: https://codecanyon.net/licenses/standard.
 *
 * ------------------------------------------------------------------------------------------------------
 */

use Akaunting\Money\Currency;
use App\Models\EmailComponent;
use App\Logics\Support\CryptoCurrency;
use App\Logics\Support\Coin;
use App\Models\PaymentMethodCategory;
use App\Models\BitcoinWallet;
use App\Models\DashWallet;
use App\Models\LitecoinWallet;
use App\Logics\Adapters\BitcoinAdapter;
use App\Logics\Adapters\DashAdapter;
use App\Logics\Adapters\LitecoinAdapter;
use App\Models\Tag;
use App\Models\Offer;
use App\Models\Trade;
use App\Models\PlatformSetting;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use GuzzleHttp\Client;


if (!function_exists('getLocale')) {
	/**
	 * @return string
	 */
	function getLocale()
	{
		return str_replace('_', '-', app()->getLocale());
	}
}


if (!function_exists('getAvailableLocales')) {
	/**
	 * @return array
	 */
	function getAvailableLocales()
	{
		$locales = app('translation-manager')->getLocales();

		if (!$locales) {
			$locales = ['en', 'es', 'zh', 'pt', 'cs', 'ms', 'it', 'de', 'ja', 'ru', 'fr'];
		}

		$supportedLocales = config('laravellocalization.supportedLocales');

		return collect($supportedLocales)
			->only($locales)
			->mapWithKeys(function ($item, $key) {
				return [$key => $item['native']];
			})
			->toArray();
	}
}

if (!function_exists('getLocaleRegion')) {

	function getLocaleRegion($locale)
	{
		$supportedLocales = config('laravellocalization.supportedLocales');

		$region = explode('_', $supportedLocales[$locale]['regional'])[1];

		return strtolower($region);
	}
}

if (!function_exists('paginate')) {
	/**
	 * Paginate collection
	 *
	 * @param $items
	 * @param int $perPage
	 * @param null $page
	 * @param array $options
	 * @return LengthAwarePaginator
	 */
	function paginate($items, $perPage = 15, $page = null, $options = [])
	{
		$options = array_merge($options, ['path' => Paginator::resolveCurrentPath()]);

		$page = $page ?: (Paginator::resolveCurrentPage() ?: 1);

		$items = $items instanceof Collection ? $items : collect($items);

		return new LengthAwarePaginator(
			$items->forPage($page, $perPage), $items->count(), $perPage, $page, $options
		);
	}
}

if (!function_exists('removeSnakeCase')) {
	/**
	 * @param string $name
	 * @return string
	 */
	function removeSnakeCase($name)
	{
		return ucwords(str_replace('_', ' ', $name));
	}
}

if (!function_exists('updateUrlQuery')) {
	/**
	 * @param \Illuminate\Http\Request $request
	 * @param array $query
	 * @return mixed
	 */
	function updateUrlQuery($request, $query)
	{
		$newQueries = array_merge($request->query(), $query);

		return $request->fullUrlWithQuery($newQueries);
	}
}

if (!function_exists('coin')) {
	/**
	 * Instance of money class.
	 *
	 * @param mixed $amount
	 * @param string $currency
	 * @param bool $convert
	 *
	 * @return \App\Logics\Support\Coin
	 */
	function coin($amount, $currency = 'BTC', $convert = false)
	{
		return new Coin($amount, new CryptoCurrency($currency), $convert);
	}
}

if (!function_exists('get_coins')) {
	/**
	 * @param $coin
	 * @return mixed
	 */
	function get_coins($coin = null)
	{
		$coins = collect(CryptoCurrency::getCurrencies())
			->mapWithKeys(function ($value, $key) {
				return [
					strtolower($key) => $value['name']
				];
			})->toArray();

		return ($coin) ? $coins[strtolower($coin)] : $coins;
	}
}

if (!function_exists('get_coin')) {
	/**
	 * @param string $coin
	 * @return mixed
	 */
	function get_coin($coin = 'btc')
	{
		return get_coins($coin);
	}
}

if (!function_exists('get_tx_preferences')) {
	/**
	 * @return mixed
	 */
	function get_tx_preferences()
	{
		return [
			'low'    => 'Low',
			'medium' => 'Medium',
			'high'   => 'High',
		];
	}
}

if (!function_exists('platform_templates')) {
	/**
	 * @return mixed
	 */
	function platform_templates()
	{
		return [
			'vertical'         => 'Vertical',
			'vertical-compact' => 'Vertical Compact',
			'vertical-overlay' => 'Vertical Overlay',
			'horizontal'       => 'Horizontal',

		];
	}
}

if (!function_exists('platform_theme_colors')) {
	/**
	 * @return mixed
	 */
	function platform_theme_colors()
	{
		return [
			'blue'      => 'Blue',
			'blue-grey' => 'Blue Grey',
			'primary'   => 'Primary',
			'danger'    => 'Danger',
			'cyan'      => 'Cyan',
			'pink'      => 'Pink',
			'success'   => 'Success',
		];
	}
}

if (!function_exists('platformSettings')) {
	/**
	 * @return \Illuminate\Database\Eloquent\Model|PlatformSetting
	 */
	function platformSettings()
	{
		return Cache::store('array')->remember(
			'platform_settings', 1,
			function () {
				$settings = PlatformSetting::first();

				if (!$settings) {
					$settings = PlatformSetting::create([
						'theme_color' => 'blue',
						'template'    => 'vertical',
					]);
				}

				return $settings;
			});

	}
}

if (!function_exists('displayUserRole')) {
	/**
	 * @param $roles
	 * @return string
	 */
	function displayUserRoles($roles)
	{
		$html = "";

		foreach ($roles as $role) {
			switch ($role) {
				case 'admin':
					$label = 'danger';
					break;
				case 'super_moderator':
					$label = 'warning';
					break;
				case 'moderator':
					$label = 'secondary';
					break;
				default:
					$label = 'primary';
					break;
			}

			$html .= "<span class='badge badge-{$label}'>";
			$html .= removeSnakeCase($role);
			$html .= "</span> ";
		}

		return $html;
	}
}

if (!function_exists('bg_status_color')) {

	/**
	 * @param string $status
	 * @return string
	 */
	function bg_status_class($status = 'default')
	{
		switch (strtolower($status)) {
			case 'success':
				return 'bg-success';
				break;

			case 'info':
				return 'bg-info';
				break;

			case 'warning':
				return 'bg-warning';
				break;

			case 'error':
			case 'danger':
				return 'bg-danger';
				break;

			default:
				return 'bg-secondary';
				break;
		}
	}
}

if (!function_exists('alert_icon')) {
	/**
	 * @param string $type
	 * @return string
	 */
	function alert_icon($type = null)
	{
		switch (strtolower($type)) {
			case 'success':
				return 'la la-check';
				break;

			case 'warning':
				return 'la la-warning';
				break;

			case 'danger':
				return 'la la-times-circle-o';
				break;

			default:
				return 'la la-info';
				break;
		}
	}
}

if (!function_exists('getProfileAvatar')) {
	/**
	 * @param \App\Models\User|null $user
	 * @return string
	 */
	function getProfileAvatar($user = null)
	{
		if ($user->profile && $user->profile->picture) {
			return $user->profile->picture;
		}

		return asset('images/objects/avatar.png');
	}
}

if (!function_exists('hasProfileAvatar')) {
	/**
	 * @param \App\Models\User|null $user
	 * @return boolean
	 */
	function hasProfileAvatar($user = null)
	{
		return ($user->profile && $user->profile->picture);
	}
}

if (!function_exists('getAvatarPath')) {
	/**
	 * @param $user
	 * @param $name
	 * @return string
	 */
	function getAvatarPath($user, $name)
	{
		return storage_path("users/{$user->id}/picture/{$name}");
	}
}


if (!function_exists('getPresenceClass')) {
	/**
	 * @param \App\Models\User|null $user
	 * @return string
	 */
	function getPresenceClass($user = null)
	{
		$class = 'avatar-off';

		switch ($user->presence) {
			case 'away':
				$class = 'avatar-away';
				break;

			case 'online':
				$class = 'avatar-online';
				break;
		}

		return $class;
	}
}

if (!function_exists('get_php_timezones')) {
	/**
	 * @return array
	 */
	function get_php_timezones()
	{
		$timezones = DateTimeZone::ALL;

		$identifier = DateTimeZone::listIdentifiers($timezones);

		return array_combine($identifier, $identifier);
	}
}


if (!function_exists('getSmsChannel')) {
	/**
	 * @return mixed
	 */
	function getSmsChannel()
	{
		$provider = config()->get('notifications.defaults.sms');

		return config()->get('notifications.sms')[$provider]['channel'];
	}
}

if (!function_exists('errorResponse')) {

	/**
	 * @param $message
	 * @param int $status
	 * @param string $ajax_type
	 * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
	 */
	function error_response($message, $status = 403)
	{
		$response = redirect()->back();

		if (!request()->ajax()) {
			toastr()->error($message);
		} else {

			if (request()->expectsJson()) {
				$response = response()->json($message, $status);
			} else {
				$response = response($message, $status);
			}

		}

		return $response;
	}
}

if (!function_exists('successResponse')) {

	/**
	 * @param $message
	 * @param int $status
	 * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
	 */
	function success_response($message, $status = 200)
	{
		$response = redirect()->back();

		if (!request()->ajax()) {
			toastr()->success($message);
		} else {

			if (request()->expectsJson()) {
				$response = response()->json($message, $status);
			} else {
				$response = response($message, $status);
			}

		}

		return $response;
	}
}


if (!function_exists('get_mail_drivers')) {
	/**
	 * @return array
	 */
	function get_mail_drivers()
	{
		return [
			"smtp"      => 'SMTP',
			"sendmail"  => 'Sendmail',
			"mailgun"   => 'Mailgun',
			"sparkpost" => 'SparkPost',
			"ses"       => 'Amazon SES'
		];
	}
}

if (!function_exists('get_sms_providers')) {
	/**
	 * @return array
	 */
	function get_sms_providers()
	{
		return [
			"nexmo"          => 'Nexmo',
			"africastalking" => 'AfricasTalking',
			"twilio"         => 'Twilio'
		];
	}
}

if (!function_exists('get_broadcast_drivers')) {
	/**
	 * @return array
	 */
	function get_broadcast_drivers()
	{
		return [
			"redis"  => 'Redis',
			"pusher" => 'Pusher'
		];
	}
}

if (!function_exists('emailComponent')) {
	/**
	 * @param string $name
	 * @return \Illuminate\Database\Eloquent\Model
	 */
	function emailComponent($name = 'default')
	{
		$key = "email_component.{$name}";

		return Cache::store('array')
			->remember($key, 1, function () use ($name) {
				$component = EmailComponent::first();

				if (!$component) {
					return EmailComponent::create(['name' => $name]);
				}

				return $component;
			});
	}
}

if (!function_exists('get_prices')) {
	/**
	 * @return array
	 */
	function get_prices()
	{
		if (app()->environment() !== 'local') {
			$expires_at = now()->addSeconds('10');
		} else {
			$expires_at = now()->addDay();
		}

		return Cache::remember('coin.prices', $expires_at, function () {
			$coins = collect(get_coins())->keys()->transform(function ($value) {
				return strtoupper($value);
			})->implode(',');

			$prices = [];

			$currencies = collect(Currency::getCurrencies())
				->mapWithKeys(function ($value, $key) {
					return [$key => $value['name']];
				})
				->sort()->keys()->transform(function ($value) {
					return strtoupper($value);
				});

			$client = new Client();

			$currencies->chunk(25)->each(function ($value) use ($coins, &$prices, $client) {
				$currencies = $value->implode(',');

				$response = $client->get("https://min-api.cryptocompare.com/data/pricemulti", [
					'query' => ['fsyms' => $coins, 'tsyms' => $currencies]
				]);

				$prices = array_merge_recursive(
					$prices, json_decode($response->getBody(), true)
				);
			});

			return $prices;
		});
	}
}

if (!function_exists('get_price')) {
	/**
	 * @param $format
	 * @param $amount
	 * @param $coin
	 * @param string $currency
	 * @return \Akaunting\Money\Money|int|float
	 */
	function get_price($amount, $coin, $currency = 'USD', $format = true)
	{
		$multiplier = get_prices()[strtoupper($coin)][strtoupper($currency)];

		$price = $amount * $multiplier;

		return ($format) ? money($price, $currency, true) : $price;
	}
}


if (!function_exists('get_iso_currencies')) {
	/**
	 * @return array
	 */
	function get_iso_currencies()
	{
		$accepted = collect(get_prices())->collapse()
			->keys()->all();

		$currency = collect(Currency::getCurrencies())
			->mapWithKeys(function ($value, $key) {
				return [
					$key => $value['name']
				];
			})->filter(function ($value, $key) use ($accepted) {
				return in_array($key, $accepted);
			});

		return $currency->sort()->all();
	}
}

if (!function_exists('get_qr_code')) {
	/**
	 * @param $text
	 * @param int $width
	 * @param int $height
	 * @return string
	 */
	function get_qr_code($text, $width = 200, $height = 200)
	{
		return "https://chart.googleapis.com/chart?cht=qr&chs={$width}x{$height}&chld=M|0&chl={$text}";
	}
}


if (!function_exists('getBlockchainAdapter')) {
	/**
	 * @param string $coin
	 * @return \App\Logics\Adapters\BitcoinAdapter|\App\Logics\Adapters\DashAdapter|\App\Logics\Adapters\LitecoinAdapter|null
	 * @throws Exception
	 */
	function getBlockchainAdapter($coin = 'btc')
	{
		$adapter = null;

		switch (strtolower($coin)) {
			case 'btc':
			case 'bitcoin':
				$adapter = new BitcoinAdapter();
				break;


			case 'dash':
				$adapter = new DashAdapter();
				break;

			case 'ltc':
			case 'litecoin':
				$adapter = new LitecoinAdapter();
				break;
		}

		return $adapter;
	}
}

if (!function_exists('getEscrowWallet')) {
	/**
	 * @param string $coin
	 * @return mixed
	 */
	function getEscrowWallet($coin = 'btc')
	{
		$model = null;

		switch (strtolower($coin)) {
			case 'btc':
			case 'bitcoin':
				$model = BitcoinWallet::whereNull('user_id');
				break;

			case 'dash':
				$model = DashWallet::whereNull('user_id');
				break;

			case 'ltc':
			case 'litecoin':
				$model = LitecoinWallet::whereNull('user_id');
				break;
		}

		return $model;
	}
}

if (!function_exists('newCoinWallet')) {
	/**
	 * @param string $coin
	 * @return mixed
	 */
	function newCoinWallet($coin = 'btc')
	{
		$model = null;

		switch (strtolower($coin)) {
			case 'btc':
			case 'bitcoin':
				$model = new BitcoinWallet();
				break;

			case 'dash':
				$model = new DashWallet();
				break;

			case 'ltc':
			case 'litecoin':
				$model = new LitecoinWallet();
				break;
		}

		return $model;
	}
}

if (!function_exists('get_tags')) {
	/**
	 * @return \Illuminate\Support\Collection
	 */
	function get_tags()
	{
		return Tag::all()->pluck('name', 'name');
	}
}

if (!function_exists('get_payment_methods')) {

	/**
	 * @return array
	 */
	function get_payment_methods()
	{
		$payment_methods = array();

		PaymentMethodCategory::all()
			->each(function ($category) use (&$payment_methods) {

				$methods = $category->payment_methods()->get()
					->pluck('name', 'name');

				$payment_methods[$category->name] = $methods;

			});

		return $payment_methods;
	}
}

if (!function_exists('share_link')) {
	/**
	 * @param $type
	 * @param $url
	 * @param $text
	 * @return string
	 */
	function share_link($type, $url, $text)
	{
		$link = '#';

		$text = urlencode($text);

		switch ($type) {
			case 'facebook':
				$link = "http://www.facebook.com/sharer/sharer.php?u={$url}";
				break;

			case 'linkedin':
				$link = "https://www.linkedin.com/shareArticle?mini=true&url={$url}&title={$text}";
				break;

			case 'twitter':
				$link = "http://twitter.com/share?url={$url}&text={$text}";
				break;
		}


		return $link;
	}
}


if (!function_exists('get_fee_percentage')) {
	/**
	 * Get trading fee
	 *
	 * @param $coin
	 * @return mixed
	 */
	function get_fee_percentage($coin = 'btc')
	{
		return (float) config()->get(
			'settings.' . strtolower($coin) . '.trade_fee', 1
		);
	}
}

if (!function_exists('calc_fee')) {

	/**
	 * Calculate trading fee
	 *
	 * @param int $amount
	 * @param string $coin
	 * @return float|int
	 */
	function calc_fee($amount, $coin = 'btc')
	{
		$percentage = get_fee_percentage($coin);

		return ($percentage * $amount) / 100;
	}
}

if (!function_exists('get_offer_title')) {
	/**
	 * @param Offer $offer
	 * @return string
	 */
	function get_offer_title(Offer $offer)
	{
		if ($offer->type == 'sell') {
			return __("Buy :coin with :payment_method from :user", [
				'coin'           => get_coin($offer->coin),
				'payment_method' => $offer->payment_method,
				'user'           => $offer->user->name
			]);
		} else {
			return __("Sell :coin for :payment_method to :user", [
				'coin'           => get_coin($offer->coin),
				'payment_method' => $offer->payment_method,
				'user'           => $offer->user->name
			]);
		}
	}
}


if (!function_exists('get_trade_title')) {
	/**
	 * @param Trade $trade
	 * @return string
	 */
	function get_trade_title(Trade $trade)
	{
		return __(':coin Trade With :payment_method', [
			'coin'           => get_coin($trade->coin),
			'payment_method' => $trade->payment_method
		]);
	}
}

if (!function_exists('user_activity')) {
	/**
	 * @param null|string $logName
	 * @return \Spatie\Activitylog\ActivityLogger
	 */
	function userActivity(string $logName = null)
	{
		$request = request();

		return activity($logName)->withProperties([
			'agent' => $request->header('User-Agent'),
			'ip'    => $request->getClientIp(),
		]);
	}
}


