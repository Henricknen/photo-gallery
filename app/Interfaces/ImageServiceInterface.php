<?php

namespace App\Interfaces;

use App\Models\Image;

interface ImageServiceInterface {       // interface de fini funções
    public function storeImageInDisk($image): string;
    public function storeImageInDataBase($title, $url): Image;
    public function deleteImageFromDisk($imageUrl): bool;
    public function deleteImageDatabaseImage($databaseImage): bool;
}
