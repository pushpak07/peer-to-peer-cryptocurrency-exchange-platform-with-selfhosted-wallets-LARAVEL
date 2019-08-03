<?php
/**
 * ======================================================================================================
 * File Name: Wallet.php
 * ======================================================================================================
 * Author: HolluwaTosin360
 * ------------------------------------------------------------------------------------------------------
 * Portfolio: http://codecanyon.net/user/holluwatosin360
 * ------------------------------------------------------------------------------------------------------
 * Date & Time: 11/3/2018 (9:06 AM)
 * ------------------------------------------------------------------------------------------------------
 *
 * Copyright (c) 2018. This project is released under the standard of CodeCanyon License.
 * You may NOT modify/redistribute this copy of the project. We reserve the right to take legal actions
 * if any part of the license is violated. Learn more: https://codecanyon.net/licenses/standard.
 *
 * ------------------------------------------------------------------------------------------------------
 */

namespace App\Logics\Support;


use App\Models\User;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Facades\Cache;

class Wallet
{
    /**
     * @var string
     */
    protected $coin;

    /**
     * @var User
     */
    protected $user;

    /**
     * Wallet constructor.
     * @param User $user
     * @param string $coin
     */
    public function __construct(User $user, $coin = 'btc')
    {
        $this->user = $user;
        $this->coin = $coin;
    }

    /**
     * Get all addresses associated with the coin
     *
     * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Support\Collection
     */
    protected function getAddresses()
    {
        $key = "user.{$this->user->id}.{$this->coin}.addresses";

        return Cache::store('array')
            ->remember($key, 1, function () {

                return $this->user
                    ->getAddressModel($this->coin)
                    ->latest()->get();

            });
    }

    /**
     * Get the latest public address
     *
     * @return string
     */
    public function latestAddress()
    {
        return $this->getAddresses()->first()->address ?? null;
    }

    /**
     * Get the latest public address QR code
     *
     * @return string
     */
    public function latestAddressQRCode()
    {
        return $this->latestAddress() ? get_qr_code(
            $this->latestAddress(), 200, 200
        ) : $this->latestAddress();
    }

    /**
     * Get total available
     *
     * @return int|float
     */
    public function totalAvailable()
    {
        return $this->user->getCoinAvailable($this->coin);
    }

    /**
     * Get total available price
     *
     * @return int|float
     */
    public function totalAvailablePrice()
    {
        return get_price(
            $this->totalAvailable(), $this->coin, $this->user->currency
        );
    }

    /**
     * Get total balance
     *
     * @return int|float
     */
    public function totalBalance()
    {
        return $this->user->getCoinBalance($this->coin);
    }

    /**
     * Get total available price
     *
     * @return int|float
     */
    public function totalBalancePrice()
    {
        return get_price(
            $this->totalBalance(), $this->coin, $this->user->currency
        );
    }
}
