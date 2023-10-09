<?php

namespace App\Services\Helpers;

class MoneyService
{
    static public function formatToDB(string|null $money) {
        $money = str_replace('.', '', $money??'');
        $money = str_replace(',', '.', $money);

        return $money;
    }

    static public function formatToUI(string|null $money) {
        return number_format(floatval($money??''), 2, ',', '.');
    }

    static public function formatToUICurrency(string|null $money) {
        return 'R$ '.number_format(floatval($money??''), 2, ',', '.');
    }
}