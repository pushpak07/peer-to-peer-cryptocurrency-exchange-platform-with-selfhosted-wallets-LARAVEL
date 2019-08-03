<?php

namespace App\Models;

use Dirape\Token\DirapeToken;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;
use willvincent\Rateable\Rateable;

class Trade extends Model
{
    use DirapeToken;

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
        'type' => 'DT_UniqueStr', 'size' => 10, 'special_chr' => false
    ];

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'created_at',
        'updated_at',
        'confirmed_at',
        'deleted_at'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function offer()
    {
        return $this->belongsTo('App\Models\Offer', 'offer_id', 'id');
    }

    /**
     * User who starts the trade
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo('App\Models\User', 'user_id', 'id');
    }

    /**
     * User who owns the offer
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function partner()
    {
        return $this->belongsTo('App\Models\User', 'partner_id', 'id');
    }

    /**
     * Grant access to view trade
     *
     * @param User $user
     * @return bool
     */
    public function grantAccess(User $user)
    {
        return $this->user->id == $user->id ||
            $this->partner->id == $user->id ||
            $user->can('resolve trade dispute');
    }

    /**
     * Determine the party of a user in a trade
     *
     * @param User $user
     * @param null $check
     * @return bool|string
     */
    public function party(User $user, $check = null)
    {
        if ($check !== null) {
            if (!is_array($check)) $check = array($check);

            $check = array_map('strtolower', $check);
        }

        $party = 'moderator';

        if ($this->partner->id == $user->id) {
            $party = ($this->type == 'sell') ? 'buyer' : 'seller';
        } elseif ($this->user->id == $user->id) {
            $party = ($this->type == 'sell') ? 'seller' : 'buyer';
        }

        return ($check !== null) ?
            in_array($party, $check) : $party;
    }

    /**
     * Get seller
     *
     * @return User|mixed|null
     */
    public function seller()
    {
        $model = null;

        if ($this->type == 'buy') {
            $model = $this->partner;
        } else {
            $model = $this->user;
        }

        return $model;
    }

    /**
     * Get buyer
     *
     * @return User|mixed|null
     */
    public function buyer()
    {
        $model = null;

        if ($this->type == 'buy') {
            $model = $this->user;
        } else {
            $model = $this->partner;
        }

        return $model;
    }

    /**
     * @throws \Exception
     */
    public function prepareEscrowWallet()
    {
        $coin = strtolower($this->coin);

        $limit = (float) config()->get("settings.{$coin}.profit_per_wallet_limit");
        $wallet = getEscrowWallet($this->coin)->where('balance', '<', $limit)
            ->latest()->first();

        if ($wallet) return $wallet;

        $adapter = getBlockchainAdapter($this->coin);
        $passphrase = str_random(10);
        $data = $adapter->generateWallet("Escrow Wallet", $passphrase);

        $wallet = newCoinWallet($this->coin);
        $wallet->fill([
            'keys'       => $data['keys'],
            'wallet_id'  => $data['id'],
            'balance'    => $data['confirmedBalance'],
            'passphrase' => $passphrase,
            'label'      => $data['label'],
        ])->save();

        $address = $data['receiveAddress'];
        $wallet->addresses()->create(['address' => $address['address']]);

        return $wallet;
    }

    /**
     * Get amount involved in trade
     *
     * @param bool $format
     * @return \Akaunting\Money\Money|mixed
     */
    public function amount($format = true)
    {
        $amount = $this->amount;

        if ($format) {
            $amount = money($amount, $this->currency, true);
        }

        return $amount;
    }

    /**
     * Get coin rate
     *
     * @param bool $format
     * @return \Akaunting\Money\Money|float|mixed
     */
    public function rate($format = true)
    {
        $rate = $this->rate;

        if ($format) {
            return money($rate, $this->currency, true);
        }

        return $rate;
    }

    /**
     * Determine coin value
     *
     * @return float
     */
    public function coinValue()
    {
        $value = $this->amount / $this->rate;

        return coin($value, $this->coin, true)->getValue();
    }

    /**
     * Calculate Fee
     *
     * @return float|int
     */
    public function calcFee()
    {
        $fee = calc_fee($this->coinValue(), $this->coin);

        return coin($fee, $this->coin, true)->getValue();
    }

    /**
     * @return bool
     */
    public function shouldDeductFee()
    {
        $threshold = (float) config()->get(
            "settings.{$this->coin}.dust_threshold"
        );

        return $this->calcFee() > $threshold;
    }

    /**
     * @return array
     * @throws \Exception
     */
    public function getOutputs()
    {
        $outputs = [];

        $wallet = $this->buyer()->getCoinWallet($this->coin)->first();

        $value = $this->coinValue();

        $outputs[] = [
            'address' => $wallet->addresses()->latest()->first()->address,
            'amount'  => (int) coin($value, $this->coin, true)->getAmount()
        ];

        $installer = resolve('installer');

        $details = $installer->purchaseDetails();

        if ($this->shouldDeductFee() && !$details->isRegularLicense()) {
            $fee = $this->calcFee();

            $wallet = $this->prepareEscrowWallet();

            $outputs[] = [
                'address' => $wallet->addresses()->latest()->first()->address,
                'amount'  => (int) coin($fee, $this->coin, true)->getAmount()
            ];
        }

        return $outputs;
    }

    /**
     * Process Transaction
     *
     * @param Trade $trade
     * @throws
     * @return array
     */
    public function processTransaction()
    {
        $wallet = $this->seller()->getCoinWallet($this->coin)
            ->first();

        $adapter = getBlockchainAdapter($this->coin);

        $tx = $adapter->sendMultiple($wallet, $this->getOutputs());

        $this->update(['status' => 'successful']);

        return $tx;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function chats()
    {
        return $this->hasMany('App\Models\TradeChat', 'trade_id', 'id');
    }

    /**
     * Group chat by date
     *
     * @return \Illuminate\Database\Eloquent\Collection|mixed
     */
    public function chatsByDate()
    {
        $chats = $this->chats()->with(
            [
                'user'         => function ($query) {
                    $query->select(['id', 'name', 'presence', 'last_seen']);
                },
                'user.profile' => function ($query) {
                    $query->select(['id', 'user_id', 'picture']);
                }
            ]
        )->get();

        if ($chats !== null) {
            $chats = $chats->groupBy(function ($item) {
                return $item->created_at->format('Y-m-d');
            })->map(function ($chats) {
                return $chats->reduce(function ($carry, $chat) {
                    if (count($carry) && $chat->user_id == $carry[count($carry) - 1]->user_id) {
                        $created_at = $chat->created_at;

                        $content = collect($carry[count($carry) - 1]->content)
                            ->push([
                                'content'    => $chat->content,
                                'created_at' => $created_at->format('Y-m-d H:i:s'),
                                'type'       => $chat->type,
                            ])
                            ->toArray();

                        $carry[count($carry) - 1]->content = $content;
                    } else {
                        $created_at = $chat->created_at;

                        $chat->content = array(
                            [
                                'content'    => $chat->content,
                                'created_at' => $created_at->format('Y-m-d H:i:s'),
                                'type'       => $chat->type,
                            ]
                        );

                        $carry->push($chat);
                    }
                    return $carry;
                }, collect());
            });
        }


        return $chats;
    }

    /**
     * @param User $user
     * @return bool
     */
    public function canRaiseDispute($user)
    {
        $method = PaymentMethod::where(
            'name', $this->payment_method
        )->first();

        if ($this->party($user, 'buyer') && $method) {
            if ($this->confirmed_at) {
                $deadline = $this->confirmed_at
                    ->addMinutes($method->time_frame);

                return $deadline < now();
            }
        }

        if($this->party($user, 'seller')){
            return true;
        }

        return false;
    }

    /**
     * Set passphrase attribute
     *
     * @param $value
     */
    public function setConfirmedAttribute($value)
    {
        $this->attributes['confirmed'] = intval($value);
        $this->attributes['confirmed_at'] = now();
    }

}
