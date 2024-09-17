<?php

namespace App\Services;
use App\Models\Image;
use Storage;

class imageService {
   public function storageImageInDisk($image) {
        $imageName = $image->store('uploads', 'public');
        return asset('storage/'.$imageName);
   } 

   public function storageImageInDataBase($title, $url) {
        return Image::create([     // salva a 'images' no banco de dados
                'title'=> $title,
                'url'=> $url
            ]);
    }

    public function deleteImageFromDisk($imageUrl) {

        $imagePath = str_replace(asset('storage/'), '', $imageUrl);
        Storage::disk('public')->delete($imagePath);     // se houver um erro no upload da imagem 'catch' dispara um 'delete'
    }

    public function deleteDatabaseImage($databaseImage) {
        if($databaseImage) {
            $databaseImage->delete();
        }
    }
}