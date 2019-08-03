<?php
/**
 * ======================================================================================================
 * File Name: CryptoCurrency.php
 * ======================================================================================================
 * Author: HolluwaTosin360
 * ------------------------------------------------------------------------------------------------------
 * Portfolio: http://codecanyon.net/user/holluwatosin360
 * ------------------------------------------------------------------------------------------------------
 * Date & Time: 11/2/2018 (11:04 AM)
 * ------------------------------------------------------------------------------------------------------
 *
 * Copyright (c) 2018. This project is released under the standard of CodeCanyon License.
 * You may NOT modify/redistribute this copy of the project. We reserve the right to take legal actions
 * if any part of the license is violated. Learn more: https://codecanyon.net/licenses/standard.
 *
 * ------------------------------------------------------------------------------------------------------
 */

namespace App\Logics\Support;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Jsonable;
use Illuminate\Contracts\Support\Renderable;
use JsonSerializable;
use OutOfBoundsException;

/**
 * Class Currency.
 *
 * @method static CryptoCurrency BTC()
 * @method static CryptoCurrency DASH()
 * @method static CryptoCurrency LTC()
 */
class CryptoCurrency implements Arrayable, Jsonable, JsonSerializable, Renderable
{
    /**
     * @var string
     */
    protected $currency;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var int
     */
    protected $precision;

    /**
     * @var int
     */
    protected $subunit;

    /**
     * @var string
     */
    protected $symbol;

    /**
     * @var bool
     */
    protected $symbolFirst;

    /**
     * @var array
     */
    protected static $currencies;

    /**
     * Create a new instance.
     *
     * @param string $currency
     *
     * @throws \OutOfBoundsException
     */
    public function __construct($currency)
    {
        $currency = strtoupper(trim($currency));
        $currencies = static::getCurrencies();

        if (!array_key_exists($currency, $currencies)) {
            throw new OutOfBoundsException('Invalid currency "' . $currency . '"');
        }

        $attributes = $currencies[$currency];
        $this->currency = $currency;
        $this->name = (string) $attributes['name'];
        $this->precision = (int) $attributes['precision'];
        $this->subunit = (int) $attributes['subunit'];
        $this->symbol = (string) $attributes['symbol'];
        $this->symbolFirst = (bool) $attributes['symbol_first'];
    }

    /**
     * __callStatic.
     *
     * @param string $method
     * @param array  $arguments
     *
     * @return CryptoCurrency
     */
    public static function __callStatic($method, array $arguments)
    {
        return new static($method);
    }

    /**
     * setCurrencies.
     *
     * @param array $currencies
     *
     * @return void
     */
    public static function setCurrencies(array $currencies)
    {
        static::$currencies = $currencies;
    }

    /**
     * getCurrencies.
     *
     * @return array
     */
    public static function getCurrencies()
    {
        if (!isset(static::$currencies)) {
            static::$currencies = config()->get('coin');
        }

        return (array) static::$currencies;
    }

    /**
     * @param self $currency
     * @return bool
     */
    public function equals(self $currency)
    {
        return $this->getCurrency() === $currency->getCurrency();
    }

    /**
     * getCurrency.
     *
     * @return string
     */
    public function getCurrency()
    {
        return $this->currency;
    }

    /**
     * getName.
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * getPrecision.
     *
     * @return int
     */
    public function getPrecision()
    {
        return $this->precision;
    }

    /**
     * getSubunit.
     *
     * @return int
     */
    public function getSubunit()
    {
        return $this->subunit;
    }

    /**
     * getSymbol.
     *
     * @return string
     */
    public function getSymbol()
    {
        return $this->symbol;
    }

    /**
     * isSymbolFirst.
     *
     * @return bool
     */
    public function isSymbolFirst()
    {
        return $this->symbolFirst;
    }

    /**
     * getPrefix.
     *
     * @return string
     */
    public function getPrefix()
    {
        if (!$this->symbolFirst) {
            return '';
        }

        return $this->symbol;
    }

    /**
     * getSuffix.
     *
     * @return string
     */
    public function getSuffix()
    {
        if ($this->symbolFirst) {
            return '';
        }

        return ' ' . $this->symbol;
    }

    /**
     * Get the instance as an array.
     *
     * @return array
     */
    public function toArray()
    {
        return [$this->currency => [
            'name'                => $this->name,
            'precision'           => $this->precision,
            'subunit'             => $this->subunit,
            'symbol'              => $this->symbol,
            'symbol_first'        => $this->symbolFirst,
            'prefix'              => $this->getPrefix(),
            'suffix'              => $this->getSuffix(),
        ]];
    }

    /**
     * Convert the object to its JSON representation.
     *
     * @param int $options
     *
     * @return string
     */
    public function toJson($options = 0)
    {
        return json_encode($this->toArray(), $options);
    }

    /**
     * jsonSerialize.
     *
     * @return array
     */
    public function jsonSerialize()
    {
        return $this->toArray();
    }

    /**
     * Get the evaluated contents of the object.
     *
     * @return string
     */
    public function render()
    {
        return $this->currency . ' (' . $this->name . ')';
    }

    /**
     * __toString.
     *
     * @return string
     */
    public function __toString()
    {
        return $this->render();
    }
}
