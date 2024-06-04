<?php

namespace App\Services\Helpers;

class AppService {
    public static function generateToken() {
        $currentTime = time();
        $interval = 60;
        $timeInterval = floor($currentTime / $interval);
        return hash('sha256', $timeInterval.env('API_TOKEN'));
    }
}