<?php

namespace App\Services;

use App\Interfaces\ImageServiceInterface;
use App\Models\Image;
use Illuminate\Support\Facades\Storage;

class imageService implements ImageServiceInterface {       // implements ImageServiceInterface  implementando interfaçe 'ImageSerrviceInterface'

    private $rollbackStack = null;      // pilha nula
    
    public function deleteImageFromDisk($imageUrl): bool {
        echo '<- deleteImageFromDisk <br>';
        $imagePath = str_replace(asset('storage/'), '', $imageUrl);
        Storage::disk('public')->delete($imagePath);     // se houver um erro no upload da imagem 'catch' dispara um 'delete'
        return true;
    }
 
    public function deleteImageDatabaseImage($databaseImage): bool {
        echo '<- deleteImageDatabaseImage <br>';
        if ($databaseImage) {
            $databaseImage->delete();
            return true;
        }
        return false;
    }

    public function rollback() {      // método 'rollback' é responsável por deleta a imagem do localstorage e do banco de dados
        while(!empty($this->rollbackStack)) {
            $rollbackAction = array_pop($this->rollback);        // pegando a ação de rollback

            $method = $rollbackAction['method'];
            $paams = $rollbackAction['params'];
            if(method_exists($this, $method)) {
                call_user_func_array([$this, $method], $params);
            }
        }

        // if(!empty($this->rollbackQueue)) {      // se a fila não estiver vazia
        //     foreach($this->rollbackQueue as $interaction) {     // percorrendo a fila rollbackQueue
        //         $method = $interaction['method'];
        //         $params = $interaction['params'];

        //         if(method_exists($this, $method)) {     // se o metodo existir
        //             call_user_func_array([$this,$method], $params);     // chama o método e os parâmetros do mesmo
        //         }
        //     }
        // }
    }

    public function storeNewImage($image, $title): image {        // método 'storareNewImage' cria a imagem e grava no banco de dados
        try {       // grava os dados, faz o 'update'
            $url = $this->storeImageInDisk($image);
            return $this->storeImageInDataBase($title, $url);
        } catch(Exception $e) {     // caso de algum erro retorna uma menssagem
            throw new Error('Erro ao salvar a imagem, tente novamente...');
        }
    }

   private function storeImageInDisk($image): string {           // método salva a imagem de forma publica e retorna um caminho para a mesma
        echo '-> storeImageInDisk <br>';
       $imageName = $image->store('uploads', 'public');             // caminho
       $url = asset('storage/' . $imageName);
       $this->addToRollbackQueue('deleteImageFromDisk', [$url]);        // passando url da imagem que será deletada
       return $url;
   } 

   private function storeImageInDataBase($title, $url): Image {     // 'private' transform em métodos espeçificos do 'ImageService'
        echo '-> storeImageInDataBase <br>';
       $image = Image::create([     // salva a 'images' no banco de dados
           'title'=> $title,
           'url'=> $url
       ]);

       $this->addToRollbackQueue('deleteImageDatabaseImage', [$image]);       // adiçionando na Queue de rollback o método 'deleteImageDatabaseImage'
       return $image;
   }

   private function addToRollbackQueue($method, $params = []) {      // método 'addToRollbackQueue' adicionará o método que será executado e os parâmetros desse método
        $this->rollbackStack[] = [
            'method' => $method,
            'params' => $params
        ];
   }

}