<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentMethodCategory extends Model
{
    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function payment_methods()
    {
        return $this->hasMany('App\Models\PaymentMethod', 'payment_method_category_id', 'id');
    }
}
