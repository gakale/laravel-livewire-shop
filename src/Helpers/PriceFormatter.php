<?php

namespace App\Helpers;

class PriceFormatter
{
    /**
     * Format a price according to the configuration
     *
     * @param float $price
     * @return string
     */
    public static function format($price)
    {
        $currency = config('livewire-shop.currency.symbol', '€');
        $position = config('livewire-shop.currency.position', 'after');
        $decimals = config('livewire-shop.currency.decimals', 2);
        $decimalSeparator = config('livewire-shop.currency.decimal_separator', ',');
        $thousandsSeparator = config('livewire-shop.currency.thousands_separator', ' ');
        
        $formattedPrice = number_format($price, $decimals, $decimalSeparator, $thousandsSeparator);
        
        return $position === 'before' 
            ? $currency . $formattedPrice 
            : $formattedPrice . ' ' . $currency;
    }
}
