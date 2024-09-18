<?php

namespace App\Interfaces;

use App\Models\Image;

interface ImageServiceInterface {       // interface defini métodos, que só podem ser públicos
    
    public function storareNewImage($image, $title): image;
    public function deleteImageFromDisk($imageUrl): bool;
    public function deleteImageDatabaseImage($databaseImage): bool;
}
