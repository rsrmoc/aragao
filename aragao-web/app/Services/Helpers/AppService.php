<?php

namespace App\Services\Helpers;

class AppService {
    public static function generateToken() {
        $currentTime = time();
      	$interval = 5*60;
        $timeInterval = floor($currentTime / $interval);
      	
        return [
          	hash('sha256', ($timeInterval-1).env('API_TOKEN')),
        	hash('sha256', $timeInterval.env('API_TOKEN')),
          	hash('sha256', ($timeInterval+1).env('API_TOKEN')),
        ];
    }
}