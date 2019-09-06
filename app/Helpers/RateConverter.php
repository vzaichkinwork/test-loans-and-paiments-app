<?php

namespace App\Helpers;

use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;

/**
 * Definitely it's better to create service & put it in the service container.
 * And we need to split this class into two (services): one for conversion and
 * one for rate loading to follow SOLID.
 *
 * And no request errors processing here.
 *
 * Class RateConverter.
 */
class RateConverter
{
    const API_URL = 'https://openexchangerates.org/api/latest.json?';

    public static function convert(float $amount, string $currencyFrom, string $currencyTo) : ?float
    {
        if ($currencyFrom === $currencyTo) {
            return $amount;
        }

        $rates = self::loadRates();

        if (!isset($rates[$currencyFrom]) || !isset($rates[$currencyTo])) {
            return null;
        }

        if ($currencyFrom === 'USD') {
            $rate = $rates[$currencyTo];
            $result = $amount * $rate;
        }
        elseif ($currencyTo === 'USD') {
            $rate = $rates[$currencyFrom];
            $result = $amount / $rate;
        }
        else {
            // Convert to USD first
            $rateFrom = $rates[$currencyFrom];
            $result = $amount / $rateFrom;
            // The convert back to 'to' currency
            $rateTo = $rates[$currencyTo];
            $result *= $rateTo;
        }

        return round($result, 2);
    }

    protected static function loadRates() : array
    {
        // Calculate seconds till the end of the day
        // to reset cache in the beginning of the next day
        $now = Carbon::now();
        $end = $now->copy()->endOfDay();
        $seconds = $end->diffInSeconds($now);

        return Cache::remember('currency-rates', $seconds, function () {
            $params = [
                'app_id' => urlencode(config('converter.app_id')),
                'base' => 'USD',
            ];

            $query = http_build_query($params);

            $json = file_get_contents(self::API_URL . $query);
            $decoded = json_decode($json, true);

            return $decoded['rates'];
        });
    }
}
