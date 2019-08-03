<?php

namespace App\Models;

use App\Logics\Support\Wallet;
use App\Notifications\Verification\EmailVerification;
use Dirape\Token\DirapeToken;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Lunaweb\EmailVerification\Traits\CanVerifyEmail;
use Lunaweb\EmailVerification\Contracts\CanVerifyEmail as CanVerifyEmailContract;
use Spatie\Activitylog\Models\Activity;
use Spatie\Permission\Traits\HasRoles;
use willvincent\Rateable\Rateable;
use willvincent\Rateable\Rating;

class User extends Authenticatable implements CanVerifyEmailContract
{
    use Notifiable, CanVerifyEmail, HasRoles, SoftDeletes, DirapeToken, Rateable;

    /**
     * The attributes that stores confirmation token
     *
     * @var string
     */
    protected $DT_Column = 'token';

    /**
     * Defines the token generation settings
     *
     * @var array
     */
    protected $DT_settings = [
        'type' => 'RandomNumber', 'size' => 6, 'special_chr' => false
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'phone', 'timezone',
        'verified', 'verified_phone', 'currency', 'google2fa_secret'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token'
    ];

    /**
     * Get the route key for the model.
     *
     * @return string
     */
    public function getRouteKeyName()
    {
        return 'name';
    }

    /**
     * Send the email verification notification.
     *
     * @param  string $token The verification mail reset token.
     * @param  int $expiration The verification mail expiration date.
     * @return void
     */
    public function sendEmailVerificationNotification($token, $expiration)
    {
        $this->notify(new EmailVerification($token, $expiration));
    }

    /**
     * Determine priority among user roles
     *
     * @param User $user
     * @return mixed
     */
    public function canManage($user = null)
    {
        if($user == null) return false;

        $condition = $this->can('edit user role') && $user->cannot('edit user role');

        return $condition;
    }

	/**
	 * Determine priority among user roles
	 *
	 * @return mixed
	 */
	public function priority()
	{
		$permissions = $this->getPermissionsViaRoles();

		$permission = $permissions->sortBy('priority')->first();

		return $permission->priority ?? 1000;
	}

	/**
     * Get all contacts saved by the instance
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function contacts()
    {
        return $this->belongsToMany('App\Models\User', 'user_contact', 'user_id', 'contact_id')
            ->withPivot('state');
    }

    /**
     * Get all users who saved the instance as a contact
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function users()
    {
        return $this->belongsToMany('App\Models\User', 'user_contact', 'contact_id', 'user_id')
            ->withPivot('state');
    }

    /**
     * Get wallet balance
     *
     * @param string $coin
     * @return mixed
     */
    public function getCoinBalance($coin = 'btc')
    {
        $coin = strtolower($coin);

        $key = "user.{$this->id}.{$coin}.balance";

        $balance = Cache::store('array')
            ->remember($key, 1, function () use ($coin) {
                $wallet =  $this->getCoinWallet($coin);

                return $wallet->first()->balance ?? 0;
            });

        return coin($balance, $coin)->getValue();
    }

    /**
     * Get wallet balance
     *
     * @param string $coin
     * @return mixed
     */
    public function getCoinAvailable($coin = 'btc')
    {
        $coin = strtolower($coin);

        $key = "user.{$this->id}.{$coin}.available";

        $available = Cache::store('array')
            ->remember($key, 1, function () use ($coin) {
                $locked_balance = (float) config()->get("settings.{$coin}.locked_balance");

                $this->activeTrades($coin)->each(function ($trade) use (&$escrow) {
                    if($trade->shouldDeductFee()){
                        $escrow += $trade->calcFee();
                    }

                    $escrow += $trade->coinValue();
                });

                $available = ($this->getCoinBalance($coin) - $escrow);

                return ($available > $locked_balance) ? ($available - $locked_balance) : 0;
            });

        return $available;
    }

    /**
     * Get all active or disputed trades
     *
     * @param string $coin
     * @return Trade[]|\Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection
     */
    public function activeTrades($coin = 'btc')
    {
        $coin = strtolower($coin);

        return Trade::where('coin', $coin)
            ->whereIn('status', ['active', 'dispute'])
            ->has('user')->has('partner')
            ->where(function ($query) {
                $query->where([
                    ['type', '=', 'buy'], ['partner_id', '=', $this->id],
                ])->orWhere([
                    ['type', '=', 'sell'], ['user_id', '=', $this->id],
                ]);
            })->get();
    }

    /**
     * Get user's profile
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function profile()
    {
        return $this->hasOne('App\Models\Profile', 'user_id', 'id');
    }

    /**
     * Return profile avatar
     *
     * @return mixed|null|string
     */
    public function profile_avatar()
    {
        if ($this->profile && $this->profile->picture) {
            return $this->profile->picture;
        }

        return asset('images/objects/avatar.png');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function offers()
    {
        return $this->hasMany('App\Models\Offer', 'user_id', 'id');
    }

    /**
     * Ger profile or create new
     *
     * @return Profile
     */
    public function getProfile()
    {
        if ($this->profile) {
        	return $this->profile;
        } else {
	        return new Profile();
        }
    }

    /**
     * Get notification settings
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function notification_setting()
    {
        return $this->hasMany('App\Models\NotificationSetting', 'user_id', 'id');
    }

    /**
     * Get notification settings or create new
     *
     * @return mixed
     */
    public function getNotificationSettings()
    {
        $default = config('notifications.settings.default');

        $settings = $this->notification_setting()->get();

        return $settings->count() ? $settings :
            $this->notification_setting()->createMany($default);
    }

    /**
     * Get address model
     *
     * @param string $coin
     * @return \Illuminate\Database\Eloquent\Relations\HasManyThrough|null
     */
    public function getAddressModel($coin = 'btc')
    {
        $model = null;

        $coin = strtolower($coin);

        switch ($coin) {
            case 'btc':
            case 'bitcoin':
                $model = $this->bitcoin_addresses();
                break;

            case 'dash':
                $model = $this->dash_addresses();
                break;

            case 'ltc':
            case 'litecoin':
                $model = $this->litecoin_addresses();
                break;
        }

        return $model;
    }

    /**
     * @param string $coin
     * @return mixed|null
     */
    public function getCoinWallet($coin = 'btc')
    {
        $model = null;

        $coin = strtolower($coin);

        switch ($coin) {
            case 'btc':
            case 'bitcoin':
                $model = $this->bitcoin_wallet();
                break;

            case 'dash':
                $model = $this->dash_wallet();
                break;

            case 'ltc':
            case 'litecoin':
                $model = $this->litecoin_wallet();
                break;
        }

        return $model;
    }

    /**
     * Get transaction model
     *
     * @param string $coin
     * @return \Illuminate\Database\Eloquent\Relations\HasManyThrough|null
     */
    public function getTransactionModel($coin = 'btc')
    {
        $model = null;

        $coin = strtolower($coin);

        switch ($coin) {
            case 'btc':
            case 'bitcoin':
                $model = $this->bitcoin_transactions();
                break;

            case 'dash':
                $model = $this->dash_transactions();
                break;

            case 'ltc':
            case 'litecoin':
                $model = $this->litecoin_transactions();
                break;
        }

        return $model;
    }

    /**
     * Get settings
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function setting()
    {
        return $this->hasOne('App\Models\UserSetting', 'user_id', 'id');
    }

    /**
     * Get user setting or create new
     *
     * @return \Illuminate\Database\Eloquent\Model|\Illuminate\Database\Eloquent\Relations\HasOne|null|object
     */
    public function getSetting()
    {
        $key = "user.{$this->id}.settings";

        return Cache::store('array')
            ->remember($key, 1, function () {

                return $this->setting()->firstOrCreate([
                    'user_id' => $this->id
                ]);

            });
    }

    /**
     * Route notifications for the Nexmo channel.
     *
     * @param  \Illuminate\Notifications\Notification $notification
     * @return string
     */
    public function routeNotificationForNexmo($notification)
    {
        return preg_replace('/\D+/', '', $this->phone);
    }

    /**
     * Route notifications for the AfricasTalking channel.
     *
     * @param  \Illuminate\Notifications\Notification $notification
     * @return string
     */
    public function routeNotificationForSms($notification)
    {
        return $this->phone;
    }

    /**
     * Generate expiring token for user
     *
     * @param int $minutes
     * @return mixed
     * @throws \Exception
     */
    public function generateToken($minutes = 5)
    {
        $token = $this->token;

        if ($this->token_expiry <= now()) {
            $this->setToken();
            $this->token_expiry = now()->addMinutes($minutes);
            $this->save();
        }

        return $token;
    }

    /**
     * Get Moderation Activities
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function moderation_activities()
    {
        return $this->hasMany('App\Models\ModerationActivity', 'user_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasManyThrough
     */
    public function bitcoin_addresses()
    {
        return $this->hasManyThrough(
            'App\Models\BitcoinAddress',
            'App\Models\BitcoinWallet',
            'user_id', 'wallet_id',
            'id', 'id'
        );
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function bitcoin_wallet()
    {
        return $this->hasOne('App\Models\BitcoinWallet', 'user_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasManyThrough
     */
    public function bitcoin_transactions()
    {
        return $this->hasManyThrough(
            'App\Models\BitcoinTransaction',
            'App\Models\BitcoinWallet',
            'user_id', 'wallet_id',
            'id', 'id'
        );
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasManyThrough
     */
    public function litecoin_addresses()
    {
        return $this->hasManyThrough(
            'App\Models\LitecoinAddress',
            'App\Models\LitecoinWallet',
            'user_id', 'wallet_id',
            'id', 'id'
        );
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function litecoin_wallet()
    {
        return $this->hasOne('App\Models\LitecoinWallet', 'user_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasManyThrough
     */
    public function litecoin_transactions()
    {
        return $this->hasManyThrough(
            'App\Models\LitecoinTransaction',
            'App\Models\LitecoinWallet',
            'user_id', 'wallet_id',
            'id', 'id'
        );
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasManyThrough
     */
    public function dash_addresses()
    {
        return $this->hasManyThrough(
            'App\Models\DashAddress',
            'App\Models\DashWallet',
            'user_id', 'wallet_id',
            'id', 'id'
        );
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function dash_wallet()
    {
        return $this->hasOne('App\Models\DashWallet', 'user_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasManyThrough
     */
    public function dash_transactions()
    {
        return $this->hasManyThrough(
            'App\Models\DashTransaction',
            'App\Models\DashWallet',
            'user_id', 'wallet_id',
            'id', 'id'
        );
    }

    /**
     * Show all trades initiated by user
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function trades()
    {
        return $this->hasMany('App\Models\Trade', 'user_id', 'id');
    }


    /**
     * Count all successful trades
     *
     * @return int
     */
    public function countSuccessfulTrades()
    {
        return Trade::where('status', 'successful')
            ->has('user')->has('partner')
            ->where(function ($query) {
                $query->where('user_id', $this->id);
                $query->orWhere('partner_id', $this->id);
            })->count();
    }

    /**
     * @param string $coin
     * @return Wallet
     */
    public function wallet($coin = 'btc')
    {
        $coin = strtolower($coin);

        $wallets = Cache::store('array')
            ->remember("user.{$this->id}.wallets", 1, function () {
                $data = [];

                foreach (get_coins() as $key => $value) {
                    $data[strtolower($key)] = new Wallet($this, $key);
                }

                return $data;
            });

        return $wallets[strtolower($coin)];
    }

	/**
	 * @return Builder
	 */
    public function activities()
    {
    	return Activity::where('causer_type', $this->getMorphClass())
		    ->where('causer_id', $this->getKey());
    }

	/**
	 * @return \Torann\GeoIP\GeoIP|\Torann\GeoIP\Location|null
	 */
    public function getLocation()
    {
    	$ip = '127.0.0.0';

    	if($activity = $this->activities()->latest()->first()){
		    $location = geoip($activity->getExtraProperty('ip'));

		    //TODO: change condition
		    return ($location['ip'] == $ip) ? $location: null;
	    }

    	return null;
    }

	/**
	 * @return mixed|\Torann\GeoIP\GeoIP|\Torann\GeoIP\Location|null
	 */
    public function getCountryCode()
    {
	    $location = $this->getLocation();

    	if($location != null) {
    		return strtolower($location['iso_code']);
	    }

    	return $location;
    }
}
