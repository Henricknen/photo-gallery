<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class GalleryController extends Controller {
    public function index() {  // função retorna a view index
        return view('index');
    }

    public function upload(Request $request) {  // função que faz o 'upload' da imagem

        if ($request->hasFile('image')) {  // verifica 'se existe' um arquivo com nome image
            $image = $request->file('image');
            $name = $image->hashName();  // gera um nome único para a imagem

            $return = $image->store('uploads', 'public');     // armazena a imagem no diretório 'uploads' no disco 'public'
        }

        return response()->json(['message' => 'No image uploaded'], 400);       // retorna um erro se nenhuma imagem for carregada
    }

    public function delete(Request $request) {          // função que 'deleta' a imagem
        
    }
}
