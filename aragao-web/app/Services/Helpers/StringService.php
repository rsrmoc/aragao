<?php

namespace App\Services\Helpers;

class StringService {
    public static function initials(string $name) {
        $names = explode(' ', $name);

        return (isset($names[0]) && !empty($names[0]) ? $names[0][0] : "").(isset($names[1]) && !empty($names[1]) ? $names[1][0] : "");;
    }
}