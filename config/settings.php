<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Coin Specific Settings
    |--------------------------------------------------------------------------
    |
    | You may define your settings like trade fee, for each coin based on your
    | interest
    |
    */

    'btc' => [
        'locked_balance' => env('SET_BTC_LOCKED_BALANCE'),
        'profit_per_wallet_limit' => env('SET_BTC_PROFIT_PER_WALLET_LIMIT', 100000000),
        'trade_fee' => env('SET_BTC_TRADE_FEE', 1),
        'dust_threshold' => 0.00003,
    ],

    'ltc' => [
        'trade_fee' => env('SET_LTC_TRADE_FEE', 1),
        'profit_per_wallet_limit' => env('SET_LTC_PROFIT_PER_WALLET_LIMIT', 100000000),
        'locked_balance' => env('SET_LTC_LOCKED_BALANCE'),
        'dust_threshold' => 0.00003,
    ],

    'dash' => [
        'trade_fee' => env('SET_DASH_TRADE_FEE', 1),
        'profit_per_wallet_limit' => env('SET_DASH_PROFIT_PER_WALLET_LIMIT', 100000000),
        'locked_balance' => env('SET_DASH_LOCKED_BALANCE'),
        'dust_threshold' => 0.00003,
    ],

    /*
    |--------------------------------------------------------------------------
    | Currency Settings
    |--------------------------------------------------------------------------
    |
    | This sets the default currency parameters to use for processing
    | offers and trades.
    | Note: Both min & max offer amounts should be in dollar.
    | It is automatically converted to user defined currency.
    |
    */

    'min_offer_amount' => env('SET_MIN_OFFER_AMOUNT', 1),
    'max_offer_amount' => env('SET_MAX_OFFER_AMOUNT', 10000),
    'default_currency' => env('SET_DEFAULT_CURRENCY', 'USD'),

    /*
   |--------------------------------------------------------------------------
   | Blockchain Settings
   |--------------------------------------------------------------------------
   |
   | You may state parameters such as the number of confirmations required to
   | update coin balance, the transaction preference which is used to determine
   | miner's fee
   |
   */
    'tx_preference' => env('SET_TX_PREFERENCE', 'medium'),

    'min_tx_confirmations' => env('SET_MIN_TX_CONFIRMATIONS', 3),

    'tx_num_blocks' => env('SET_TX_NUM_BLOCKS', 7),
];
