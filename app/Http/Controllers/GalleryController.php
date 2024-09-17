<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\Dimensions;
use App\Models\Image; 
use Exception;
class GalleryController extends Controller {
    public function index() {  // função retorna a view index
        $image = Image::all();      // trazendo as imagens do bd para a tela
        return view('index', ['images'=> $image]);
    }

    public function upload(Request $request) {  // função que faz o 'upload' da imagem

        $this->validateRequest($request);       // utilizando função 'validateRequest' que faz validção dos dados
        
        $title = $request->only('title');
        $image = $request->file('image');

        // $image->hashName();  // gera um nome único para a imagem

        try {
            $url = $this->storageImageInDisk($image);       // ultilizando função 'storageImageInDisk' que salvará a imagem no localstorage

        } catch(Exception $error) {
            return redirect()->back()->withErrors([
                'error'=> 'Erro ao salvar a imagem. Tente novamente.'
            ]);
        }
        
        try {
            Image::create([     // salva a 'images' no banco de dados
                'title'=> $title['title'],
                'url'=> $url
            ]);
        } catch(Exception $error) {
            Storage::disk('public')->delete($imageName);     // se houver um erro no upload da imagem 'catch' dispara um 'delete'
            return redirect()->back()->withErrors([
                'error'=> 'Erro ao salvar a imagem. Tente novamente.'
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

    private function validateRequest(Request $request) {        // função recebe uma request e faz uma validação
        $request->validate([        // validado dados do 'titulo' e da 'imagem'
            'title'=> 'required|string|max:255|min:6',
            'image'=> [
                'required',
                'image',
                'mimes:jpeg,png,jpg,gif',
                'max:6114',
                (new Dimensions)->maxWidth(5500)->maxHeight(5500)     // tamanho maximo das imagens permitidas
            ]
        ]);
    }

    private function storageImageInDisk($image) {     // salva a imagem no 'localstorage'
        $imageName = $image->store('uploads', 'public');     // armazena a imagem no diretório 'uploads' no disco 'public'
        return asset('storage/'.$imageName);
    }
}