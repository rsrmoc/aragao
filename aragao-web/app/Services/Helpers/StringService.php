<?php

namespace App\Services\Helpers;

class StringService {
    public static function initials(string $name) {
        $names = explode(' ', $name);

        return $names[0][0].(isset($names[1]) ? $names[1][0] : "");
    }
}