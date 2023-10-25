<?php

namespace App\Services\Helpers;

class StatusService {
    public static function classStyleStatusObra(string $status, string $prefix) {
        return match($status) {
            'Em andamento' => "{$prefix}-warning",
            'Atrasada' => "{$prefix}-error",
            'Concluida' => "{$prefix}-success",
        };
    }

    public static function classStyleStatusReuniao(string $status, string $prefix) {
        return match($status) {
            'agendada' => "{$prefix}-info",
            'confirmada' => "{$prefix}-accent",
            'adiada' => "{$prefix}-warning",
            'cancelada' => "{$prefix}-error",
            'concluida' => "{$prefix}-success",
            'negada' => "{$prefix}-error",
            'conteudo_pendente' => "{$prefix}-warning"
        };
    }
}