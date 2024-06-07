<?php

namespace App\Services\Helpers;

use App\Models\Imagens;
use Illuminate\Support\Facades\Storage;

class ImagensService {
    
    public static function upload($dados) {
        $nomeArquivo = uniqid().'.'.$dados['tipo'];
        $arquivo = base64_decode($dados['imagem']);
        $path = 'public/imagens/'.$nomeArquivo;

        $dados['url'] = Storage::url($path);
        unset($dados['imagem']);

        Storage::put($path, $arquivo);
        Imagens::create($dados);
    }

}