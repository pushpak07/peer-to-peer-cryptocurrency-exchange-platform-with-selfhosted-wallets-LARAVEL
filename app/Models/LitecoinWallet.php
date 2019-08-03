<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LitecoinWallet extends Model
{
    use WalletSecurityLayer;

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * Get wallet addresses
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function addresses()
    {
        return $this->hasMany('App\Models\LitecoinAddress', 'wallet_id', 'id');
    }

    /**
     * Get wallet transactions
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function transactions()
    {
        return $this->hasMany('App\Models\LitecoinTransaction', 'wallet_id', 'id');
    }

    /**
     * Get user
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo('App\Models\User', 'user_id', 'id');
    }
}
