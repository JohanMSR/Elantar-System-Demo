<?php

namespace App\Services\Documents;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class DocumentUserService
{
    protected $storagePath = 'user_documents';
    protected $storageImagePath = 'img_profile';
    
    public function uploadDocument(Request $request, $fieldName, $document,$user)
    {
        $rutaDestino = '';
        try {
            if ($request->hasFile($fieldName)){
                if($request->file($fieldName)->isValid())
                {   
                    if (isset($document) && !empty($document))
                    {
                        $this->deleteDocument($document);
                    }
                    
                    $file = $request->file($fieldName);
                    $extension = $file->getClientOriginalExtension();
                    
                    $rutaDestino = storage_path('app/public/temp/' . $file->getClientOriginalName());
                    $file->move(dirname($rutaDestino), basename($rutaDestino));
                    
                    $lastName = $user.'_'.Str::afterLast($fieldName, '_');
                    //$lastName = Str::afterLast($fieldName, '_').'_'.Str::random(40);
                    if(strtolower($extension) === 'pdf'){
                        $nombre_temp = $lastName.'.'.$file->getClientOriginalExtension();
                        $path = $this->storagePath . '/' .$nombre_temp;
                        //Storage::disk('public')->put($path,$nombre_temp);
                        Storage::disk('public')->put($path, file_get_contents($rutaDestino));
                    }else{
                        $manager = new ImageManager(new Driver());
                        $image = $manager->read($rutaDestino);
                        // Obtener las dimensiones originales de la imagen
                        $originalWidth = $image->width();
                        $originalHeight = $image->height();

                            // Calcular las nuevas dimensiones manteniendo la proporción
                        $maxWidth = 1920;
                        $maxHeight = 1080;

                        $width = $originalWidth;
                        $height = $originalHeight;

                        if ($originalWidth > $maxWidth || $originalHeight > $maxHeight) {
                            $ratio = min($maxWidth / $originalWidth, $maxHeight / $originalHeight);
                            $width = (int)round($originalWidth * $ratio);
                            $height = (int)round($originalHeight * $ratio);
                        }

                        // Redimensionar la imagen
                        $image->resize($width, $height);
                        $nombre_temp = $lastName.'.'.$file->getClientOriginalExtension();
                        $path = $this->storagePath . '/' .$nombre_temp;
                        Storage::disk('public')->put($path, $image->encode());
                    }
                    
                    
                    $url = '/'.$path;
                    //Borra el archivo temporal
                    if (file_exists($rutaDestino)) {
                        unlink($rutaDestino);
                    }
                }
                else
                {
                    Log::Error('Document services el archivo no es valido');
                    throw new \Exception('El archivo no es válido.');
                }
            }else{
                return null;
            }    
            return $url;
    
        } catch (\Exception $e) {

            return null;            
        }       
    }
    
    public function deleteDocument($filePath)
    {

        try {
            // Asegúrate de que el archivo exista antes de intentar eliminarlo
            if (Storage::disk('local')->exists('public/' . $filePath)) {
                Storage::disk('local')->delete('public/' . $filePath);
                return true;
            } else {
                return false; // O lanza una excepción si prefieres indicar que el archivo no se encontró
            }


        } catch (\Exception $e) {
            // Maneja las excepciones de eliminación de archivos aquí
            return false;  // O lanza una nueva excepción si lo prefieres
        }
    }
    
    public function uploadImagePerfil(Request $request, $fieldName, $document)
    {
        $rutaDestino = '';
        
        try {
            
            if ($request->hasFile($fieldName)){
                
                if($request->file($fieldName)->isValid())
                {   
                    if (isset($document) && !empty($document))
                    {
                        $this->deleteDocument($document);
                    }
                    
                    $file = $request->file($fieldName);                    
                    
                    $rutaDestino = storage_path('app/public/temp/' . $file->getClientOriginalName());
                    $file->move(dirname($rutaDestino), basename($rutaDestino));                    
                    
                    $lastName = Str::random(40);
                    $manager = new ImageManager(new Driver());
                    $image = $manager->read($rutaDestino);
                    $originalWidth = $image->width();
                    $originalHeight = $image->height();
                    // Calcular las nuevas dimensiones manteniendo la proporción
                    $maxWidth = 1920;
                    $maxHeight = 1080;

                    $width = $originalWidth;
                    $height = $originalHeight;

                    if ($originalWidth > $maxWidth || $originalHeight > $maxHeight) {
                            $ratio = min($maxWidth / $originalWidth, $maxHeight / $originalHeight);
                            $width = (int)round($originalWidth * $ratio);
                            $height = (int)round($originalHeight * $ratio);
                    }

                    // Redimensionar la imagen
                    $image->resize($width, $height);
                    $nombre_temp = $lastName.'.'.$file->getClientOriginalExtension();
                        
                    $path = $this->storageImagePath . '/' .$nombre_temp;
                    Storage::disk('public')->put($path, $image->encode());
                    
                    $url = '/'.$path;
                    //Borra el archivo temporal
                    if (file_exists($rutaDestino)) {
                        unlink($rutaDestino);
                    }
                }
                else
                {
                    Log::Error('Document User services la foto de perfil no es valido');
                    throw new \Exception('El archivo no es válido.');
                }
            }else{
                return null;
            }    
            return $url;
    
        } catch (\Exception $e) {
            return null;            
        }       
    }

}