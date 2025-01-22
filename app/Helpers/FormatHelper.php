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

if (!function_exists('terbilang')) {
    function terbilang($angka)
    {
        $angka = abs($angka);
        $huruf = ["", "Satu", "Dua", "Tiga", "Empat", "Lima", "Enam", "Tujuh", "Delapan", "Sembilan", "Sepuluh", "Sebelas"];
        $temp = "";

        if ($angka < 12) {
            $temp = " " . $huruf[$angka];
        } elseif ($angka < 20) {
            $temp = terbilang($angka - 10) . " Belas ";
        } elseif ($angka < 100) {
            $temp = terbilang($angka / 10) . " Puluh " . terbilang($angka % 10);
        } elseif ($angka < 200) {
            $temp = " Seratus " . terbilang($angka - 100);
        } elseif ($angka < 1000) {
            $temp = terbilang($angka / 100) . " Ratus " . terbilang($angka % 100);
        } elseif ($angka < 2000) {
            $temp = " Seribu " . terbilang($angka - 1000);
        } elseif ($angka < 1000000) {
            $temp = terbilang($angka / 1000) . " Ribu " . terbilang($angka % 1000);
        } elseif ($angka < 1000000000) {
            $temp = terbilang($angka / 1000000) . " Juta " . terbilang($angka % 1000000);
        } elseif ($angka < 1000000000000) {
            $temp = terbilang($angka / 1000000000) . " Miliar " . terbilang($angka % 1000000000);
        }

        return trim($temp);
    }
}
