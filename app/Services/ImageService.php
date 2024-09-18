<?php

namespace App\Services;

use App\Interfaces\ImageServiceInterface;
use App\Models\Image;
use Illuminate\Support\Facades\Storage;

class imageService implements ImageServiceInterface {       // implements ImageServiceInterface  implementando interfaçe 'ImageSerrviceInterface'

    public function storareNewImage($image, $title): image {        // método 'storareNewImage' cria a imagem e grava no banco de dados
        try {       // grava os dados, faz o 'update'
            $url = $this->storeImageInDisk($image);
            return $this->storeImageInDataBase($title, $url);
        } catch(Exception $e) {     // caso de algum erro retorna uma menssagem
            throw new Error('Erro ao salvar a imagem, tente novamente...');
        }
    }

   private function storeImageInDisk($image): string {           // método salva a imagem de forma publica e retorna um caminho para a mesma
       $imageName = $image->store('uploads', 'public');             // caminho
       return asset('storage/' . $imageName);
   } 

   private function storeImageInDataBase($title, $url): Image {     // 'private' transform em métodos espeçificos do 'ImageService'
       return Image::create([     // salva a 'images' no banco de dados
           'title'=> $title,
           'url'=> $url
       ]);
   }

   public function deleteImageFromDisk($imageUrl): bool {
       $imagePath = str_replace(asset('storage/'), '', $imageUrl);
       Storage::disk('public')->delete($imagePath);     // se houver um erro no upload da imagem 'catch' dispara um 'delete'
       return true;
   }

   public function deleteImageDatabaseImage($databaseImage): bool {
       if ($databaseImage) {
           $databaseImage->delete();
           return true;
       }
       return false;
   }
}