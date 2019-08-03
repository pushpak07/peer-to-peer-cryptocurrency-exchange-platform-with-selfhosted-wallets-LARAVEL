<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::group([
	'prefix'     => LaravelLocalization::setLocale(),
	'middleware' => ['localeSessionRedirect', 'localizationRedirect', 'localeViewPath']
], function () {
	// Landing routes
	Route::get('/', function () {
		$root_url = platformSettings()->root_url;

		if (!$root_url || Auth::check()) {
			return redirect()->route('home.index');
		}

		return redirect()->away($root_url);
	});

	// Authentication route
	Route::group(['namespace' => 'Auth'], function () {
		// Login Routes
		Route::group(['prefix' => 'login'], function () {
			Route::get('', 'LoginController@showLoginForm')->name('login');
			Route::post('', 'LoginController@login');
			Route::post('check-2fa', 'LoginController@check2FA')->name('login.check-2fa');
		});

		Route::post('logout', 'LoginController@logout')->name('logout');


		// Registration Routes...
		Route::group(['prefix' => 'register'], function () {
			Route::get('', 'RegisterController@showRegistrationForm')->name('register');
			Route::post('', 'RegisterController@register');

			Route::get('verify', 'RegisterController@verify')->name('verifyEmailLink');
		});

		// Password Reset Routes...
		Route::group([
			'prefix' => 'password',
			'as'     => 'password.'
		], function () {
			// Reset
			Route::group(['prefix' => 'reset'], function () {
				Route::get('', 'ForgotPasswordController@showLinkRequestForm')->name('request');
				Route::get('{token}', 'ResetPasswordController@showResetForm')->name('reset');
				Route::post('', 'ResetPasswordController@reset');
			});

			Route::post('email', 'ForgotPasswordController@sendResetLinkEmail')->name('email');
		});

	});

	// Profile
	Route::group([
		'as'     => 'profile.',
		'prefix' => 'profile'
	], function () {
		// Picture
		Route::get('{user_name}/storage/{picture}', 'ProfileController@picture')->name('picture');
	});

	// ThirdParty Services
	Route::group(['namespace' => 'Services'], function () {
		// BlockCypher
		Route::group([
			'as'     => 'bitgo.',
			'prefix' => 'bitgo'
		], function () {
			// Webhook
			Route::group([
				'as'     => 'hook.',
				'prefix' => 'hook'
			], function () {
				// Bitcoin
				Route::post('btc', 'BitGo\WebhookController@handleBitcoin')->name('btc');

				// Dash
				Route::post('dash', 'BitGo\WebhookController@handleDash')->name('dash');

				// Litecoin
				Route::post('ltc', 'BitGo\WebhookController@handleLitecoin')->name('ltc');
			});
		});
	});

	// Dashboard Routes
	Route::middleware('auth')->group(function () {
		// Stateless API
		Route::group([
			'as'     => 'ajax.',
			'prefix' => 'ajax'
		], function () {

			// Profile
			Route::group([
				'as'     => 'profile.',
				'prefix' => 'profile/{user}'
			], function () {
				// Get Notifications
				Route::post('unread-notifications', 'ProfileController@unreadNotifications')->name('unreadNotifications');

				// Set Away Status
				Route::put('away', 'ProfileController@setAway')->name('setAway');

				// Get Trade Chats
				Route::post('active-trade-chats', 'ProfileController@activeTradeChats')->name('activeTradeChats');
				// Set Online Status

				Route::put('online', 'ProfileController@setOnline')->name('setOnline');

				// Confirm Phone
				Route::post('confirm-phone', 'ProfileController@confirmPhone')->name('confirmPhone');

				// Resend Email Verification
				Route::post('resend-verification-email', 'ProfileController@resendVerificationEmail')->name('resendVerificationEmail');

				// Get Ratings
				Route::post('get-ratings', 'ProfileController@getRatings')->name('get-ratings');

				// Resend Phone Verification
				Route::post('resend-verification-sms', 'ProfileController@resendVerificationSms')->name('resendVerificationSms');
			});
		});

		// Home Page
		Route::group([
			'as'     => 'home.',
			'prefix' => 'home'
		], function () {
			// Index
			Route::get('', 'Home\HomeController@index')->name('index');

			// Successful Trades Data
			Route::post('trades-data', 'Home\HomeController@tradesData')->name('trades-data');

			Route::group([
				'as'     => 'offers.',
				'prefix' => 'offer'
			], function () {
				// Actions
				Route::group(['prefix' => '{token}'], function () {
					// Index
					Route::get('', 'Home\OffersController@index')->name('index');

					// Edit
					Route::get('edit', 'Home\OffersController@edit')->name('edit');

					// Update
					Route::post('update', 'Home\OffersController@update')->name('update');

					// Start Trade
					Route::post('start-trade', 'Home\OffersController@startTrade')->name('start-trade');

					// Delete
					Route::delete('delete', 'Home\OffersController@delete')->name('delete');

					// Toggle
					Route::post('toggle', 'Home\OffersController@toggle')->name('toggle');
				});

				// Offers Data
				Route::post('data', 'Home\OffersController@data')->name('data');
			});

			Route::group([
				'as'     => 'trades.',
				'prefix' => 'trade'
			], function () {
				// Actions
				Route::group(['prefix' => '{token}'], function () {
					// Index
					Route::get('', 'Home\TradesController@index')->name('index');

					// Confirm
					Route::post('confirm', 'Home\TradesController@confirm')->name('confirm');

					// Complete
					Route::post('complete', 'Home\TradesController@complete')->name('complete');

					// Dispute
					Route::post('dispute', 'Home\TradesController@dispute')->name('dispute');

					// Send Message
					Route::post('send-message', 'Home\TradesController@sendMessage')->name('send-message');

					// Upload Media
					Route::post('upload-media', 'Home\TradesController@uploadMedia')->name('upload-media');

					// Download
					Route::get('file/{name}', 'Home\TradesController@download')->name('download');

					// Cancel
					Route::post('cancel', 'Home\TradesController@cancel')->name('cancel');

					// Rate
					Route::post('rate', 'Home\TradesController@rate')->name('rate');
				});

				// Offers Data
				Route::post('data', 'Home\TradesController@data')->name('data');
			});

		});

		Route::group([
			'as'        => 'market.',
			'prefix'    => 'market',
			'namespace' => 'Market'
		], function () {
			// Create Offer
			Route::group([
				'as'     => 'create-offer.',
				'prefix' => 'create-offer'
			], function () {
				// Buy
				Route::get('buy', 'CreateOfferController@buyIndex')->name('buy');

				// Sell
				Route::get('sell', 'CreateOfferController@sellIndex')->name('sell');

				// Store
				Route::post('{type}', 'CreateOfferController@store')->name('store');
			});

			// Buy Coin
			Route::group([
				'as'     => 'buy-coin.',
				'prefix' => 'buy-coin'
			], function () {
				// Index
				Route::get('', 'BuyCoinController@index')->name('index');

				// Data
				Route::post('data', 'BuyCoinController@data')->name('data');
			});

			// Sell Coin
			Route::group([
				'as'     => 'sell-coin.',
				'prefix' => 'sell-coin'
			], function () {
				// Index
				Route::get('', 'SellCoinController@index')->name('index');

				// Data
				Route::post('data', 'SellCoinController@data')->name('data');
			});
		});

		// Wallet Page
		Route::group([
			'as'     => 'wallet.',
			'prefix' => 'wallet'
		], function () {
			// Index
			Route::get('', 'WalletController@index')->name('index');

			// Generate Address
			Route::post('{coin}/generate-address', 'WalletController@generateAddress')->name('generate-address');

			// Address Data
			Route::post('{coin}/address-data', 'WalletController@addressData')->name('address-data');

			// Transaction Data
			Route::post('{coin}/transaction-data', 'WalletController@transactionData')->name('transaction-data');

			// Send Asset
			Route::post('{coin}/send', 'WalletController@send')->name('send');

		});

		// Profile Page
		Route::group([
			'as'        => 'profile.',
			'namespace' => 'Profile',
			'prefix'    => 'profile/{user}',
		], function () {
			// Index
			Route::get('', 'ProfileController@index')->name('index');

			// Ratings Data
			Route::post('ratings-data', 'ProfileController@ratingsData')->name('ratings-data');

			// Moderation
			Route::group(['middleware' => 'permission:edit user settings'], function () {
				// Deactivate
				Route::post('deactivate', 'ProfileController@deactivate')->name('deactivate');
			});

			// Offer Data
			Route::post('offers-data', 'ProfileController@offersData')->name('offers-data');

			// Private Details
			Route::group(['middleware' => 'permission.view_user_details'], function () {
				// Notifications
				Route::group([
					'as'     => 'notifications.',
					'prefix' => 'notifications'
				], function () {
					// Index
					Route::get('', 'NotificationsController@index')->name('index');

					// markAllAsRead
					Route::post('markAllAsRead', 'NotificationsController@markAllAsRead')->name('markAllAsRead');

					// markAllAsRead
					Route::post('{id}/markAsRead', 'NotificationsController@markAsRead')->name('markAsRead');

					// Notifications Data
					Route::post('data', 'NotificationsController@data')->name('data');
				});

				// Trades
				Route::group([
					'as'     => 'trades.',
					'prefix' => 'trades'
				], function () {
					// Index
					Route::get('', 'TradesController@index')->name('index');

					// Data
					Route::post('data', 'TradesController@data')->name('data');
				});
			});

			// Settings
			Route::group([
				'middleware' => 'permission.edit_user_settings',
				'as'         => 'settings.',
				'prefix'     => 'settings'
			], function () {
				// Index
				Route::get('', 'SettingsController@index')->name('index');

				// Update Verification
				Route::post('update-verification', 'SettingsController@updateVerification')->name('update-verification');

				// Update Profile
				Route::post('update-profile', 'SettingsController@updateProfile')->name('update-profile');

				// Update Preferences
				Route::post('update-preferences', 'SettingsController@updatePreferences')->name('update-preferences');

				// Update Settings
				Route::post('update-settings', 'SettingsController@updateSettings')->name('update-settings');

				// Update Password
				Route::post('update-password', 'SettingsController@updatePassword')->name('update-password');

				Route::middleware('permission:edit user role')->group(function () {
					// Update Role
					Route::post('update-role', 'SettingsController@updateRole')->name('update-role');
				});

				// Moderation Activity Data
				Route::post('moderation-activity-data', 'SettingsController@moderationActivityData')->name('moderation-activity-data');

				// Upload Picture
				Route::post('upload-picture', 'SettingsController@uploadPicture')->name('upload-picture');

				// Delete Picture
				Route::post('delete-picture', 'SettingsController@deletePicture')->name('delete-picture');

				// Delete Account
				Route::post('delete-account', 'SettingsController@deleteAccount')->name('delete-account');
			});

			// Two Factor Authentication
			Route::group([
				'middleware' => 'permission.edit_user_settings',
				'as'         => '2fa.',
				'prefix'     => '2fa'
			], function () {
				// Setup
				Route::post('setup', 'TwoFactorController@setup')->name('setup');

				// Reset
				Route::post('reset', 'TwoFactorController@reset')->name('reset');
			});

			// Contacts
			Route::group([
				'as'     => 'contacts.',
				'prefix' => 'contacts'
			], function () {
				// Index
				Route::get('', 'ContactsController@index')->name('index');

				// Unblock
				Route::put('unblock', 'ContactsController@unblock')->name('unblock');

				// Block
				Route::put('block', 'ContactsController@block')->name('block');

				// Add
				Route::put('add', 'ContactsController@add')->name('add');

				// Delete
				Route::put('delete', 'ContactsController@delete')->name('delete');

				// Untrust
				Route::put('untrust', 'ContactsController@untrust')->name('untrust');

				// Trust
				Route::put('trust', 'ContactsController@trust')->name('trust');

				// Contacts Data
				Route::post('data', 'ContactsController@data')->name('data');
			});
		});

		// Admin Routes
		Route::group([
			'as'         => 'admin.',
			'namespace'  => 'Admin',
			'middleware' => 'permission:access admin panel',
			'prefix'     => 'admin',
		], function () {
			// Home Page
			Route::group([
				'as'     => 'home.',
				'prefix' => 'home'
			], function () {
				// Index
				Route::get('', 'HomeController@index')->name('index');

				// Visible Offers
				Route::post('visible-offers', 'HomeController@visibleOffers')->name('visible-offers');

				// Completed Trades
				Route::post('completed-trades', 'HomeController@completedTrades')->name('completed-trades');

				// Online Users
				Route::post('online-users', 'HomeController@onlineUsers')->name('online-users');
			});

			// Users Page
			Route::group([
				'as'     => 'users.',
				'prefix' => 'users'
			], function () {
				// Index
				Route::get('', 'UsersController@index')->name('index');

				// Deactivate
				Route::post('deactivate', 'UsersController@deactivate')->name('deactivate');

				// DataTable
				Route::post('data', 'UsersController@data')->name('data');

				// Activate
				Route::post('activate', 'UsersController@activate')->name('activate');

				// Delete
				Route::post('delete', 'UsersController@delete')->name('delete');

				// Restore
				Route::post('restore', 'UsersController@restore')->name('restore');

				// Trash
				Route::post('trash', 'UsersController@trash')->name('trash');
			});

			// Users Page
			Route::group([
				'as'         => 'earnings.',
				'middleware' => 'permission:manage earnings',
				'prefix'     => 'earnings',
			], function () {
				// Index
				Route::get('', 'EarningsController@index')->name('index');

				// Payout
				Route::post('payout', 'EarningsController@payout')->name('payout');

				// Coin specific routes
				Route::group(['prefix' => '{coin}'], function () {
					// Data
					Route::post('data', 'EarningsController@data')->name('data');
				});

				// Update
				Route::post('update', 'EarningsController@update')->name('update');
			});

			// Settings
			Route::group([
				'as'        => 'settings.',
				'prefix'    => 'settings',
				'namespace' => 'Settings'
			], function () {
				// General
				Route::group(['as' => 'general.', 'prefix' => 'general'], function () {
					// Index
					Route::get('', 'GeneralController@index')->name('index');

					// Update General
					Route::post('update', 'GeneralController@update')->name('update');
				});

				// Notification
				Route::group(['as' => 'notification.', 'prefix' => 'notification'], function () {
					// Index
					Route::get('', 'NotificationController@index')->name('index');

					// Update General
					Route::post('update-general', 'NotificationController@updateGeneral')->name('update-general');

					// Update Component
					Route::post('update-component', 'NotificationController@updateComponent')->name('update-component');

					// Update Template
					Route::post('update-template', 'NotificationController@updateTemplate')->name('update-template');
				});

				// Transaction
				Route::group(['as' => 'transaction.', 'prefix' => 'transaction'], function () {
					// Index
					Route::get('', 'TransactionController@index')->name('index');

					// Update General
					Route::post('update', 'TransactionController@update')->name('update');
				});

				// Offer settings Page
				Route::group(['as' => 'offer.', 'prefix' => 'offer'], function () {
					// Index
					Route::get('', 'OfferController@index')->name('index');

					// Payment Methods Store
					Route::post('store-payment-method', 'OfferController@storePaymentMethod')->name('store-payment-method');

					// Payment Method Categories Store
					Route::post('store-payment-category', 'OfferController@storePaymentCategory')->name('store-payment-category');

					// Offer Tags Store
					Route::post('store-offer-tag', 'OfferController@storeOfferTag')->name('store-offer-tag');

					// Payment Methods Delete
					Route::delete('delete-payment-method', 'OfferController@deletePaymentMethod')->name('delete-payment-method');

					// Payment Method Categories Delete
					Route::delete('delete-payment-category', 'OfferController@deletePaymentCategory')->name('delete-payment-category');

					// Offer Tags Delete
					Route::delete('delete-offer-tag', 'OfferController@deleteOfferTag')->name('delete-offer-tag');

					// Payment Methods Data
					Route::post('payment-methods-data', 'OfferController@paymentMethodsData')->name('payment-methods-data');

					// Payment Method Categories Data
					Route::post('payment-categories-data', 'OfferController@paymentCategoriesData')->name('payment-categories-data');

					// Offer Tags Data
					Route::post('offer-tags-data', 'OfferController@offerTagsData')->name('offer-tags-data');

				});
			});

			// Platform
			Route::group([
				'as'        => 'platform.',
				'prefix'    => 'platform',
				'namespace' => 'Platform'
			], function () {
				// Customize
				Route::group(['as' => 'customize.', 'prefix' => 'customize'], function () {
					// Index
					Route::get('', 'CustomizeController@index')->name('index');

					// Update
					Route::post('', 'CustomizeController@update');
				});

				// Translation
				Route::group(['as' => 'translation.', 'prefix' => 'translation'], function () {
					// Index
					Route::get('', 'TranslationController@index')->name('index');

					// Find Translation
					Route::post('find-translation', 'TranslationController@findTranslation')->name('find-translation');

					// Import Translation
					Route::post('import-translation', 'TranslationController@importTranslation')->name('import-translation');

					// Create Locale
					Route::post('add-locale', 'TranslationController@addLocale')->name('add-locale');

					// Delete Locale
					Route::post('remove-locale', 'TranslationController@removeLocale')->name('remove-locale');

					// Export
					Route::post('export', 'TranslationController@export')->name('export');

					// Translation Groups
					Route::group(['as' => 'group.', 'prefix' => '{group}'], function () {
						// Edit
						Route::get('', 'TranslationController@groupEdit')->name('edit');

						// Update
						Route::put('update', 'TranslationController@groupUpdate')->name('update');

						// Export
						Route::post('export', 'TranslationController@groupExport')->name('export');

						// Data
						Route::post('data', 'TranslationController@groupData')->name('data');
					});

					// Update
					Route::post('', 'TranslationController@update');
				});

				// Integration
				Route::group(['as' => 'integration.', 'prefix' => 'integration'], function () {
					// Index
					Route::get('', 'IntegrationController@index')->name('index');

					// Update
					Route::post('', 'IntegrationController@update');
				});

				// License
				Route::group(['as' => 'license.', 'prefix' => 'license'], function () {
					// Index
					Route::get('', 'LicenseController@index')->name('index');

					// Update
					Route::post('', 'LicenseController@update');
				});
			});
		});

		Route::group([
			'as'         => 'moderation.',
			'namespace'  => 'Moderation',
			'middleware' => 'permission:resolve trade dispute',
			'prefix'     => 'moderation',
		], function () {
			// Manage trades Page
			Route::group(['as' => 'trades.', 'prefix' => 'trades'], function () {
				// Index
				Route::get('', 'TradesController@index')->name('index');

				// Data
				Route::post('data', 'TradesController@data')->name('data');
			});
		});
	});
});

