<?php

namespace App\Services\Firebase;

use Exception;
use Illuminate\Support\Facades\Http;

class FBCloudMessagingService {
    protected $api = 'https://fcm.googleapis.com/fcm';
    protected $apiKey = 'AAAAQoS7CjU:APA91bGyLFpyzyzbUgMp_U_-IlFDzTgM2fhKG5QE7gK_kgTVm-vnQL4MlBznMre-MDrOUwfd6gg9FBdxSH-FxIRr-oYCVZG75eE5lXa9uzhzrkDYMZqEmDW3hVMNRHUfOAcgRr4Lgwmf';

    protected $apiHeaders;

    public function __construct() {
        $this->apiHeaders = [
            'Content-Type' => 'application/json',
            'Authorization' => "key={$this->apiKey}"
        ];
    }
    
    public function sendMessage(string $deviceId, string $title, string $body) {
        try {
            Http::withHeaders($this->apiHeaders)
                ->post("{$this->api}/send", [
                    'to' => $deviceId,
                    'notification' => [
                        'title' => $title,
                        'body' => $body
                    ]
                ]);
        }
        catch(Exception $e) {}
    }
}