<?php
/**
 * ======================================================================================================
 * File Name: AddressSecurityLayer.php
 * ======================================================================================================
 * Author: HolluwaTosin360
 * ------------------------------------------------------------------------------------------------------
 * Portfolio: http://codecanyon.net/user/holluwatosin360
 * ------------------------------------------------------------------------------------------------------
 * Date & Time: 10/19/2018 (1:56 PM)
 * ------------------------------------------------------------------------------------------------------
 *
 * Copyright (c) 2018. This project is released under the standard of CodeCanyon License.
 * You may NOT modify/redistribute this copy of the project. We reserve the right to take legal actions
 * if any part of the license is violated. Learn more: https://codecanyon.net/licenses/standard.
 *
 * ------------------------------------------------------------------------------------------------------
 */

namespace App\Models;


trait WalletSecurityLayer
{
    /**
     * Set passphrase attribute
     *
     * @param $value
     */
    public function setPassphraseAttribute($value)
    {
        $this->attributes['passphrase'] = encrypt($value);
    }

    /**
     * Get passphrase attribute
     *
     * @param $value
     * @return mixed
     */
    public function getPassphraseAttribute($value)
    {
        return decrypt($value);
    }

    /**
     * Set keys attribute
     *
     * @param $value
     */
    public function setKeysAttribute($value)
    {
        $this->attributes['keys'] = json_encode($value);
    }

    /**
     * Get keys attribute
     *
     * @param $value
     * @return mixed
     */
    public function getKeysAttribute($value)
    {
        return json_decode($value, true);
    }
}
