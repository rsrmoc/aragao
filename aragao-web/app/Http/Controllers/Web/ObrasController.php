<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Imagens;
use App\Models\ObraRelatorio;
use App\Models\Obras;
use App\Models\ObrasEtapas;
use App\Models\ObrasEvolucoes;

class ObrasController extends Controller {
    public function renderizarRelatorio($idRelatorio) {
        $relatorio = ObraRelatorio::find($idRelatorio);

        $obra = Obras::find($relatorio->id_obra);
        $etapas = ObrasEtapas::where('id_obra', $obra)->where('created_at', '<=', $relatorio->created_at)->orderBy('created_at', 'desc')->get();
        $porcGeral = ObrasEtapas::where('id_obra', $obra)->where('created_at', '<=', $relatorio->created_at)->get()->sum('insidencia_executada');
        $evolucoes = ObrasEvolucoes::with(['etapa', 'usuario'])->where('id_obra', $obra)->where('created_at', '<=', $relatorio->created_at)->orderBy('created_at', 'desc')->get();

        return response()->view(
            'pdf.obras-relatorios', compact('obra', 'etapas', 'porcGeral', 'evolucoes')
        );
    }

    public function blobImagem($id) {
        $imagem = Imagens::find($id);
        return response()->json([
            'url' => $imagem->url
        ]);
    }
}