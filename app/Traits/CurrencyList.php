<?php
namespace App\Traits;

trait CurrencyList {
    protected function currency_list()
    {
        $url = "https://openexchangerates.org/api/latest.json?app_id=".env('OPEN_EXCHANGE_RATES_APP_ID');
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_URL,$url);
        $result=curl_exec($ch);
        curl_close($ch);
        return (json_decode($result, true));
    }
}