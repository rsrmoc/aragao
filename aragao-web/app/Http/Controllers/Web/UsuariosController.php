<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;

class UsuariosController extends Controller
{
    public function index() {
        return view('pages.dashboard.usuarios');
    }

    public function localizacao(User $usuario) {
        return view('pages.dashboard.usuarios-localizacao', compact('usuario'));
    }

    public function tokenNotification(Request $request) {
        $request->validate([
            'token' => 'required|string',
            'platform' => 'required|string|in:android,ios'
        ]);

        try {
            $userTokenExists = User::where('id', '<>', $request->user()->id)
                ->where("notification_token_{$request->platform}", $request->token)
                ->first();

            if ($userTokenExists)
                $userTokenExists->update(["notification_token_{$request->platform}" => null]);

            $request->user()->update(["notification_token_{$request->platform}" => $request->token]);

            return response()->json(['message' => 'token updated!']);
        }
        catch(Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }
}
