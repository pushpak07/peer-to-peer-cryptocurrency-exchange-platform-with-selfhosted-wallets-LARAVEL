<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DashTransaction extends Model
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
        return $this->belongsTo('App\Models\DashWallet', 'wallet_id', 'id');
    }
}
