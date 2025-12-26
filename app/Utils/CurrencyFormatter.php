<?php

namespace App\Utils;

/**
 * Currency Formatter for RWF (Rwandan Franc)
 */
class CurrencyFormatter
{
    const CURRENCY_CODE = 'RWF';
    const CURRENCY_SYMBOL = 'FRw';
    const DECIMAL_PLACES = 0;

    /**
     * Format amount to RWF currency string
     */
    public static function format($amount, $includeSymbol = true): string
    {
        if ($amount === null) {
            return $includeSymbol ? 'FRw 0' : '0';
        }

        $formatted = number_format((float)$amount, self::DECIMAL_PLACES, '.', ',');

        if ($includeSymbol) {
            return self::CURRENCY_SYMBOL . ' ' . $formatted;
        }

        return $formatted;
    }

    /**
     * Format amount to display format (short form)
     */
    public static function formatShort($amount): string
    {
        $amount = (float)$amount;

        if ($amount >= 1000000) {
            return 'FRw ' . round($amount / 1000000, 1) . 'M';
        } elseif ($amount >= 1000) {
            return 'FRw ' . round($amount / 1000, 1) . 'K';
        }

        return 'FRw ' . number_format($amount, 0);
    }

    /**
     * Get currency symbol
     */
    public static function getSymbol(): string
    {
        return self::CURRENCY_SYMBOL;
    }

    /**
     * Get currency code
     */
    public static function getCode(): string
    {
        return self::CURRENCY_CODE;
    }

    /**
     * Parse currency string to number
     */
    public static function parse($value): float
    {
        if (is_numeric($value)) {
            return (float)$value;
        }

        // Remove currency symbol and formatting
        $value = str_replace(self::CURRENCY_SYMBOL, '', $value);
        $value = str_replace('FRw', '', $value);
        $value = str_replace(',', '', $value);
        $value = trim($value);

        return (float)$value;
    }
}
