<?php

if (!function_exists('currency_convert')) {
    /**
     * Convert given number.
     *
     * @param float  $amount
     * @param string $from
     * @param string $to
     * @param bool   $format
     *
     * @return \Torann\Currency\Currency|string
     */
    function currency_convert($amount = null, $from = null, $to = null, $format = false)
    {
        if (is_null($amount)) {
            return app('currency');
        }

        return app('currency')->convert($amount, $from, $to, $format);
    }
}

