<?php

namespace App\Services;

use App\Interfaces\ImageServiceInterface;
use App\Models\Image;
use Illuminate\Support\Facades\Storage;

class imageService implements ImageServiceInterface {       // implements ImageServiceInterface  implementando interfaçe 'ImageSerrviceInterface'
   public function storeImageInDisk($image): string {           // método salva a imagem de forma publica e retorna um caminho para a mesma
       $imageName = $image->store('uploads', 'public');             // caminho
       return asset('storage/' . $imageName);
   } 

   public function storeImageInDataBase($title, $url): Image {
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