<?php

if (!function_exists('format_currency')) {
    /**
     * Format a number into currency.
     *
     * @param float|int $amount
     * @param string $currency
     * @param int $decimals
     * @return string
     */
    function format_currency($amount, $currency = 'IDR', $decimals = 0)
    {
        $locale = 'id_ID'; // Locale untuk format Indonesia
        $formatter = new NumberFormatter($locale, NumberFormatter::CURRENCY);

        return $formatter->formatCurrency($amount, $currency) ?: number_format($amount, $decimals);
    }
}
