<?php

use Carbon\Carbon;

if (!function_exists('format_date')) {
    /**
     * Format a date into the specified format.
     *
     * @param string|DateTime $date
     * @param string $format
     * @return string
     */
    function format_date($date, $format = 'd-m-Y')
    {
        if (!$date) {
            return '-';
        }

        return Carbon::parse($date)->format($format);
    }
}

if (!function_exists('format_date_human')) {
    /**
     * Format a date into a human-readable format.
     *
     * @param string|DateTime $date
     * @return string
     */
    function format_date_human($date)
    {
        if (!$date) {
            return '-';
        }

        return Carbon::parse($date)->diffForHumans();
    }
}

if (!function_exists('format_date_locale')) {
    /**
     * Format a date based on locale settings.
     *
     * @param string|DateTime $date
     * @param string $locale
     * @param string $format
     * @return string
     */
    function format_date_locale($date, $locale = 'id_ID', $format = '%d %B %Y')
    {
        if (!$date) {
            return '-';
        }

        setlocale(LC_TIME, $locale);
        return strftime($format, strtotime($date));
    }
}
