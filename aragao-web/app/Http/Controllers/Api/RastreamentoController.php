<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Rastreamentos;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RastreamentoController extends Controller {
    public function gravarLocalizacao(Request $request) {
        $data = $request->validate([
            'latitude' => 'required|string',
            'longitude' => 'required|string',
            'id_usuario' => 'required|exists:users,id',
        ]);

        try {
            $user_info = User::where('id', $data['id_usuario'])->first();

            if ($user_info && $user_info->engineer_location == 0) {
                return response()->json([
                    'message' => 'Usuário não armazena localização',
                    'interval' => Rastreamentos::INTERVALO_CAPTURA
                ]);
            }

            $data['endereco'] = $this->buscarEndereco($data['latitude'], $data['longitude']);

            DB::beginTransaction();
            Rastreamentos::create($data);
            DB::commit();

            return response()->json([
                'message' => 'Registro gravado com sucesso',
                'interval' => Rastreamentos::INTERVALO_CAPTURA
            ]);
        }
        catch(Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => $e->getMessage(),
                'interval' => Rastreamentos::INTERVALO_CAPTURA
            ], 500);
        }
    }

    private function buscarEndereco($lat, $long) {
        try {
            $endpointUrl = 'https://nominatim.openstreetmap.org/reverse.php?lat=' . $lat . '&lon=' . $long . '&zoom=18&format=jsonv2';

            $ch = curl_init($endpointUrl);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:59.0) Gecko/20100101 Firefox/59.0");
            $response = curl_exec($ch);
            curl_close($ch);

            $data = json_decode($response, true);
            return $data['address']['road'] . ' - ' . $data['address']['suburb'] . ' - ' . $data['address']['city'] . ' - ' . $data['address']['state'];
        } catch (\Throwable $th) {
            return 'Endereço não encontrado. Latitude: ' . $lat . ' Longitude: ' . $long;
        }
    }
}
