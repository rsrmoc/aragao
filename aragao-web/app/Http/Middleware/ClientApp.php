<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class ClientApp
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $tokenRequest = $request->header('Token');
        if (!$tokenRequest) {
            return response()->json([
                'message' => 'Token não informado'
            ], 401);
        }

        $tokensBackend = \App\Services\Helpers\AppService::generateToken();
        if (!in_array($tokenRequest, $tokensBackend)) {
            return response()->json([
                'message' => 'Token inválido'
            ], 401);
        }

        return $next($request);
    }
}
