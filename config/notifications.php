<?php

return [
    'defaults' => [
        'sms' => env('SMS_PROVIDER', 'nexmo')
    ],

    'sms' => [
        'nexmo' => [
            'channel' => 'nexmo'
        ],

        'twilio' => [
	        'channel' => \App\Channels\TwilioChannel::class
        ],

        'africastalking' => [
            'channel' => \App\Channels\AfricasTalkingChannel::class
        ],

        'msg91' => [
            'channel' => \App\Channels\Msg91Channel::class
        ],

    ],

    'settings' => [

        'default' => [
            [
                'name' => 'coin_incoming_confirmed',
                'description' => 'Coin incoming confirmed',
                'email' => true,
                'database' => true,
                'sms' => false,
            ],

            [
                'name' => 'coin_incoming_unconfirmed',
                'description' => 'Coin incoming unconfirmed',
                'email' => true,
                'database' => true,
                'sms' => null,
            ],

            [
                'name' => 'new_trade',
                'description' => 'New Trade',
                'email' => true,
                'database' => true,
                'sms' => true,
            ],

            [
                'name' => 'buyer_paid_for_trade',
                'description' => 'Buyer Paid For Trade',
                'email' => true,
                'database' => true,
                'sms' => true,
            ],

            [
                'name' => 'trade_cancelled_or_expired',
                'description' => 'Trade Cancelled or Expired',
                'email' => true,
                'database' => true,
                'sms' => null,
            ],
        ]
    ]
];
