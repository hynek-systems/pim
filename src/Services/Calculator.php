<?php

namespace Hynek\Pim\Services;

abstract class Calculator
{
    /**
     * Calculate the total amount after applying tax, discount, or markup.
     *
     * @param  float  $amount  The base amount.
     * @param  float  $taxRate  The tax rate as a percentage.
     * @return float The calculated total amount.
     */
    public static function addTax(float $amount, float $taxRate): float
    {
        return round($amount * (1 + $taxRate / 100));
    }

    /**
     * Subtract tax from the amount to get the net amount.
     *
     * @param  float  $amount  The total amount including tax.
     * @param  float  $taxRate  The tax rate as a percentage.
     * @return float The net amount after subtracting tax.
     */
    public static function subtractTax(float $amount, float $taxRate): float
    {
        return round($amount / (1 + $taxRate / 100));
    }

    /**
     * Calculate the tax amount based on the given amount and tax rate.
     *
     * @param  float  $amount  The base amount.
     * @param  float  $taxRate  The tax rate as a percentage.
     * @return float The calculated tax amount.
     */
    public static function calculateTax(float $amount, float $taxRate): float
    {
        return round($amount * ($taxRate / 100));
    }

    /**
     * Calculate the discount amount based on the given amount and discount rate.
     *
     * @param  float  $amount  The base amount.
     * @param  float  $discountRate  The discount rate as a percentage.
     * @return float The calculated discount amount.
     */
    public static function calculateDiscount(float $amount, float $discountRate): float
    {
        return round($amount * ($discountRate / 100));
    }

    /**
     * Apply a discount to the given amount based on the discount rate.
     *
     * @param  float  $amount  The base amount.
     * @param  float  $discountRate  The discount rate as a percentage.
     * @return float The amount after applying the discount.
     */
    public static function applyDiscount(float $amount, float $discountRate): float
    {
        return round($amount * (1 - $discountRate / 100));
    }

    /**
     * Apply a markup to the given amount based on the markup rate.
     *
     * @param  float  $amount  The base amount.
     * @param  float  $markupRate  The markup rate as a percentage.
     * @return float The amount after applying the markup.
     */
    public static function applyMarkup(float $amount, float $markupRate): float
    {
        return round($amount * (1 + $markupRate / 100));
    }

    /**
     * Calculate the markup amount based on the given amount and markup rate.
     *
     * @param  float  $amount  The base amount.
     * @param  float  $markupRate  The markup rate as a percentage.
     * @return float The calculated markup amount.
     */
    public static function calculateMarkup(float $amount, float $markupRate): float
    {
        return round($amount * ($markupRate / 100));
    }

    /**
     * Calculate the total amount after applying tax to the base amount.
     *
     * @param  float  $amount  The base amount.
     * @param  float  $taxAmount  The tax amount to be added.
     * @return float The total amount after adding tax.
     */
    public static function calculateTotal(float $amount, float $taxAmount): float
    {
        return $amount + $taxAmount;
    }

    /**
     * Calculate the net amount by subtracting the tax amount from the total amount.
     *
     * @param  float  $totalAmount  The total amount including tax.
     * @param  float  $taxAmount  The tax amount to be subtracted.
     * @return float The net amount after subtracting tax.
     */
    public static function calculateNetAmount(float $totalAmount, float $taxAmount): float
    {
        return $totalAmount - $taxAmount;
    }

    /**
     * Calculate the gross amount by adding the tax amount to the net amount.
     *
     * @param  float  $netAmount  The net amount before tax.
     * @param  float  $taxAmount  The tax amount to be added.
     * @return float The gross amount after adding tax.
     */
    public static function calculateGrossAmount(float $netAmount, float $taxAmount): float
    {
        return $netAmount + $taxAmount;
    }

    /**
     * Round the given amount to the specified precision.
     *
     * @param  float  $amount  The amount to be rounded.
     * @param  int  $precision  The number of decimal places to round to (default is 2).
     * @return float The rounded amount.
     */
    public static function roundAmount(float $amount, int $precision = 2): float
    {
        return round($amount, $precision);
    }

    /**
     * Calculate the exchange rate for a given amount and exchange rate.
     *
     * @param  float  $amount  The amount to be converted.
     * @param  float  $exchangeRate  The exchange rate to be applied.
     * @return float The converted amount after applying the exchange rate.
     */
    public static function calcExchangeRate(float $amount, float $exchangeRate): float
    {
        return round($amount * $exchangeRate, 2);
    }
}
