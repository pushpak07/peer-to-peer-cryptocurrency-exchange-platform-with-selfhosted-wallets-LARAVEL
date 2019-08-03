<?php
/**
 * ======================================================================================================
 * File Name: Coin.php
 * ======================================================================================================
 * Author: HolluwaTosin360
 * ------------------------------------------------------------------------------------------------------
 * Portfolio: http://codecanyon.net/user/holluwatosin360
 * ------------------------------------------------------------------------------------------------------
 * Date & Time: 11/2/2018 (10:07 AM)
 * ------------------------------------------------------------------------------------------------------
 *
 * Copyright (c) 2018. This project is released under the standard of CodeCanyon License.
 * You may NOT modify/redistribute this copy of the project. We reserve the right to take legal actions
 * if any part of the license is violated. Learn more: https://codecanyon.net/licenses/standard.
 *
 * ------------------------------------------------------------------------------------------------------
 */

namespace App\Logics\Support;


class Coin
{
    /**
     * @var string
     */
    protected $coin;

    /**
     * @var CryptoCurrency
     */
    protected $currency;

    /**
     * @var int|float
     */
    protected $amount;

    /**
     * Coin constructor.
     *
     * @param $amount
     * @param CryptoCurrency $currency
     * @param bool $convert
     */
    public function __construct($amount, $currency, $convert = false)
    {
        $this->currency = $currency;
        $this->amount = $this->parseAmount($amount, $convert);
    }

    /**
     * parseAmount.
     *
     * @param mixed $amount
     * @param bool  $convert
     *
     * @throws \UnexpectedValueException
     *
     * @return int|float
     */
    protected function parseAmount($amount, $convert = false)
    {
        $amount = $this->parseAmountFromString($amount);

        if (is_int($amount)) {
            return (int) $this->convertAmount($amount, $convert);
        }

        if (is_float($amount)) {
            return (float) round(
                $this->convertAmount($amount, $convert), $this->currency->getPrecision()
            );
        }

        throw new \UnexpectedValueException('Invalid amount "' . $amount . '"');
    }

    /**
     * parseAmountFromString.
     *
     * @param mixed $amount
     *
     * @return int|float|mixed
     */
    protected function parseAmountFromString($amount)
    {
        if (!is_string($amount)) {
            return $amount;
        }

        if (preg_match('/^([\-\+])?\d+$/', $amount)) {
            $amount = (int) $amount;
        } elseif (preg_match('/^([\-\+])?\d+\.\d+$/', $amount)) {
            $amount = (float) $amount;
        }

        return $amount;
    }

    /**
     * convertAmount.
     *
     * @param int|float $amount
     * @param bool      $convert
     *
     * @return int|float
     */
    protected function convertAmount($amount, $convert = false)
    {
        if (!$convert) {
            return $amount;
        }

        return $amount * $this->currency->getSubunit();
    }

    /**
     * getAmount.
     *
     * @return int|float
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * getValue.
     *
     * @return float
     */
    public function getValue()
    {
        return round($this->amount / $this->currency->getSubunit(), $this->currency->getPrecision());
    }

    /**
     * Convert to String.
     *
     * @return string
     */
    public function __toString()
    {
        return $this->getValue();
    }
}
