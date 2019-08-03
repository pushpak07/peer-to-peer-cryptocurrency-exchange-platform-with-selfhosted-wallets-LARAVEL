<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BitcoinTransaction extends Model
{
    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];


    /**
     * Get user wallet
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function wallet()
    {
        return $this->belongsTo('App\Models\BitcoinWallet', 'wallet_id', 'id');
    }

}
