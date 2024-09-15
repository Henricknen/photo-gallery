<?php

namespace App\Http\Controllers;

use App\Models\Image; 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class GalleryController extends Controller {
    public function index() {  // função retorna a view index
        $image = Image::all();      // trazendo as imagens do bd para a tela
        return view('index', ['images'=> $image]);
    }

    public function upload(Request $request) {  // função que faz o 'upload' da imagem
        
        if ($request->hasFile('image')) {  // verifica 'se existe' um arquivo com nome image
            $title = $request->only('title');       // titulo da imagem
            $image = $request->file('image');
            $name = $image->hashName();  // gera um nome único para a imagem

            $return = $image->store('uploads', 'public');     // armazena a imagem no diretório 'uploads' no disco 'public'
            $url = asset('storage/'.$return);

            Image::create([     // armazena 'title' e 'url' na tabela 'images' do banco de dados
                'title'=> $title['title'],
                'url'=> $url
            ]);
        }

        return redirect()-> route('index');     // retorna para o index 'automaticamente'
    }

    public function delete($id) {          // função que 'deleta' a imagem
         $image = Image::findOrFail($id);
         $url = parse_url($image->url);     // transforma o caminho da imagem do banco de dados em uma url
         $path = ltrim($url['path'], '/storage\/');     // 'ltrim' remove a pasta storage
         
         if(Storage::disk('public')->exists($path)) {       // se a imagem existir
            Storage::disk('public')->delete($path);     // exclui do 'localstorage'
             $image->delete();      // em seguida exclui a imagem do banco de dados            
         }

         return redirect()->route('index');
    }
}
