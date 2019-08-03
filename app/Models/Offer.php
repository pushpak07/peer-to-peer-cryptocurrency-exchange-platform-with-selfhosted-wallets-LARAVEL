<?php

namespace App\Models;

use Dirape\Token\DirapeToken;
use Illuminate\Database\Eloquent\Model;

class Offer extends Model
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
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo('App\Models\User', 'user_id', 'id')
            ->where('status', 'active');
    }

    /**
     * Get the tags array.
     *
     * @param  string $value
     * @return array
     */
    public function getTagsAttribute($value)
    {
        return explode(',', $value);
    }

    /**
     * Set the tags array.
     *
     * @param  array $value
     * @return void
     */
    public function setTagsAttribute($value)
    {
        $this->attributes['tags'] = implode(',', $value);
    }

    /**
     * @param User $user
     * @return bool
     */
    public function trust(User $user)
    {
        $blocked_contact = $this->user->contacts()
            ->wherePivot('state', 'block')
            ->find($user->id);

        $trusted = true;

        if ($this->trusted_offer) {
            $trusted_contact = $this->user->contacts()
                ->wherePivot('state', 'trust')
                ->find($user->id);

            $trusted = ($trusted_contact || $user->id == $this->user->id);
        }


        return $trusted && !$blocked_contact;
    }

    /**
     * Get currency multiplier
     *
     * @return float|int
     */
    public function multiplier()
    {
        if($this->type == 'sell'){
            $multiplier = (100 + $this->profit_margin) / 100;
        }else{
            $multiplier = (100 - $this->profit_margin) / 100;
        }

        return $multiplier;
    }

    /**
     * @param User $user
     * @return bool|int|mixed
     */
    public function verifyPhone(User $user)
    {
        return ($this->phone_verification) ? $user->verified_phone : true;
    }

    /**
     * @param User $user
     * @return bool|mixed
     */
    public function verifyEmail(User $user)
    {
        return ($this->email_verification) ? $user->verified : true;
    }

    /**
     * @param User $user
     * @return bool
     */
    public function canTradeWith(User $user)
    {
        return $this->verifyEmail($user)
            && ($user->id !== $this->user->id)
            && $this->verifyPhone($user);
    }

    /**
     * @param User|null $user
     * @param bool $public
     * @return bool
     */
    public function canShow($user = null, $public = false)
    {
        if($user && !$this->trust($user)) {
            return false;
        }

        if($this->user->schedule_deactivate || $this->user->schedule_delete){
            return ($user)? $user->id == $this->user->id: false;
        }

        if ($this->type == 'sell') {
            $balance = $this->user->getCoinAvailable($this->coin);

            $available = get_price($balance, $this->coin, $this->currency, false);

            $fee = calc_fee($this->max_amount, $this->coin);

            if($user && !$public){
                return (($this->max_amount + $fee) <= $available) || $user->id == $this->user->id;
            }else{
                return (($this->max_amount + $fee) <= $available);
            }
        }

        return true;
    }
}
