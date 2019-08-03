<?php

return [

	/*
	|--------------------------------------------------------------------------
	| Third Party Services
	|--------------------------------------------------------------------------
	|
	| This file is for storing the credentials for third party services such
	| as Stripe, Mailgun, SparkPost and others. This file provides a sane
	| default location for this type of information, allowing packages
	| to have a conventional place to find your various credentials.
	|
	*/

	'mailgun' => [
		'domain' => env('MAILGUN_DOMAIN'),
		'secret' => env('MAILGUN_SECRET'),
	],

	'ses' => [
		'key'    => env('SES_KEY'),
		'secret' => env('SES_SECRET'),
		'region' => env('SES_REGION', 'us-east-1'),
	],

	'pusher' => [
		'key'     => env('PUSHER_APP_KEY'),
		'cluster' => env('PUSHER_APP_CLUSTER')
	],

	'sparkpost' => [
		'secret' => env('SPARKPOST_SECRET'),
	],

	'stripe' => [
		'model'  => App\Models\User::class,
		'key'    => env('STRIPE_KEY'),
		'secret' => env('STRIPE_SECRET'),
	],

	'nocaptcha' => [
		'enable' => env('NOCAPTCHA_ENABLE', false),
		'key'    => env('NOCAPTCHA_SITEKEY'),
		'secret' => env('NOCAPTCHA_SECRET'),
		'type'   => env('NOCAPTCHA_TYPE'),
	],

	'nexmo' => [
		'key'      => env('NEXMO_KEY'),
		'secret'   => env('NEXMO_SECRET'),
		'sms_from' => env('NEXMO_PHONE'),
	],

	'africastalking' => [
		'username' => env('AFRICASTALKING_USERNAME'),
		'key'      => env('AFRICASTALKING_KEY'),
		'from'     => env('AFRICASTALKING_FROM'),
		'enqueue'  => env('AFRICASTALKING_ENQUEUE', true)
	],

	'msg91' => [
		'key'     => env('MSG91_KEY'),
		'sender'  => env('MSG91_SENDER'),
		'country' => env('MSG91_COUNTRY'),
		'route'   => env('MSG91_ROUTE'),
	],

	'twilio' => [
		'token'  => env('TWILIO_TOKEN'),
		'id'     => env('TWILIO_ID'),
		'number' => env('TWILIO_NUMBER'),
	],

	'bitgo' => [
		'env'   => env('BITGO_ENV', 'test'),
		'token' => env('BITGO_TOKEN'),
		'host'  => env('BITGO_HOST'),
		'port'  => env('BITGO_PORT'),
	],
];
