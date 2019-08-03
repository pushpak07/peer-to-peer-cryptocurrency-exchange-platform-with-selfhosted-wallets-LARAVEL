<?php
/**
 * ======================================================================================================
 * File Name: breadcrumbs.blade.php
 * ======================================================================================================
 * Author: HolluwaTosin360
 * ------------------------------------------------------------------------------------------------------
 * Portfolio: http://codecanyon.net/user/holluwatosin360
 * ------------------------------------------------------------------------------------------------------
 * Date & Time: 9/1/2018 (7:15 PM)
 * ------------------------------------------------------------------------------------------------------
 *
 * Copyright (c) 2018. This project is released under the standard of CodeCanyon License.
 * You may NOT modify/redistribute this copy of the project. We reserve the right to take legal actions
 * if any part of the license is violated. Learn more: https://codecanyon.net/licenses/standard.
 *
 * ------------------------------------------------------------------------------------------------------
 */

// Home
Breadcrumbs::for('home', function ($trail) {
    $trail->push(__('Home'), route('home.index'));
});

// Wallet
Breadcrumbs::for('wallet', function ($trail) {
    $trail->parent('home');
    $trail->push(__('Wallet'), route('wallet.index'));
});

// Buy Coin
Breadcrumbs::for('market.buy_coin', function ($trail) {
    $trail->parent('home');
    $trail->push(__('Buy Coin'), route('market.buy-coin.index'));
});

// Buy Offer
Breadcrumbs::for('buy_offer', function ($trail, $token) {
    $trail->parent('market.buy_coin');
    $trail->push(__('Offer'), route('home.offers.index', ['token' => $token]));
});

// Edit Buy Offer
Breadcrumbs::for('edit_buy_offer', function ($trail, $token) {
    $trail->parent('buy_offer', $token);
    $trail->push(__('Edit'), route('home.offers.edit', ['token' => $token]));
});

// Sell Coin
Breadcrumbs::for('market.sell_coin', function ($trail) {
    $trail->parent('home');
    $trail->push(__('Sell Coin'), route('market.sell-coin.index'));
});

// Sell Offer
Breadcrumbs::for('sell_offer', function ($trail, $token) {
    $trail->parent('market.sell_coin');
    $trail->push(__('Offer'), route('home.offers.index', ['token' => $token]));
});

// Edit Sell Offer
Breadcrumbs::for('edit_sell_offer', function ($trail, $token) {
	$trail->parent('sell_offer', $token);
	$trail->push(__('Edit'), route('home.offers.edit', ['token' => $token]));
});


// Create Buy Offer
Breadcrumbs::for('market.create_offer.buy', function ($trail) {
    $trail->parent('home');
    $trail->push(__('Create Buy Offer'), route('market.create-offer.buy'));
});

// Create Sell Offer
Breadcrumbs::for('market.create_offer.sell', function ($trail) {
    $trail->parent('home');
    $trail->push(__('Create Sell Offer'), route('market.create-offer.sell'));
});

// Admin Home
Breadcrumbs::for('admin.home', function ($trail) {
    $trail->push(__('Home'), route('admin.home.index'));
});

// Users
Breadcrumbs::for('admin.earnings', function ($trail) {
    $trail->parent('admin.home');
    $trail->push(__('Earnings'), route('admin.earnings.index'));
});

// Users
Breadcrumbs::for('admin.users', function ($trail) {
    $trail->parent('admin.home');
    $trail->push(__('Users'), route('admin.users.index'));
});

// General Settings
Breadcrumbs::for('admin.settings.general', function ($trail) {
    $trail->parent('admin.home');
    $trail->push(__('General Settings'), route('admin.settings.notification.index'));
});

// Notification Settings
Breadcrumbs::for('admin.settings.notification', function ($trail) {
    $trail->parent('admin.home');
    $trail->push(__('Notification Settings'), route('admin.settings.notification.index'));
});

// Transaction Settings
Breadcrumbs::for('admin.settings.transaction', function ($trail) {
    $trail->parent('admin.home');
    $trail->push(__('Transaction Settings'), route('admin.settings.transaction.index'));
});

Breadcrumbs::for('admin.platform.customize', function ($trail) {
    $trail->parent('admin.home');
    $trail->push(__('Customize'), route('admin.platform.customize.index'));
});

Breadcrumbs::for('admin.platform.translation', function ($trail) {
    $trail->parent('admin.home');
    $trail->push(__('Translation'), route('admin.platform.translation.index'));
});

Breadcrumbs::for('admin.platform.translation.group.edit', function ($trail, $name) {
    $trail->parent('admin.platform.translation');
    $trail->push(__('Edit'), route('admin.platform.translation.group.edit', ['name' => $name]));
});

Breadcrumbs::for('admin.platform.integration', function ($trail) {
    $trail->parent('admin.home');
    $trail->push(__('Integration'), route('admin.platform.integration.index'));
});

Breadcrumbs::for('admin.platform.license', function ($trail) {
    $trail->parent('admin.home');
    $trail->push(__('License'), route('admin.platform.license.index'));
});

// Profile
Breadcrumbs::for('profile', function ($trail, $name) {
    $trail->parent('home');
    $trail->push(__('Profile'), route('profile.index', compact('name')));
});

