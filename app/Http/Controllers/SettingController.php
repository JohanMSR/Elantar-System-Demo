<?php

namespace App\Http\Controllers;

use App\Http\Requests\SettingRequest;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\File;
use Intervention\Image\ImageManager;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules;
use Intervention\Image\Colors\Rgb\Channels\Red;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\DB;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\Drivers\Imagick;
use Illuminate\Support\Facades\Log;

class SettingController extends Controller
{
    
    protected $directory  = 'public/img_profile';
    protected $imageDirectory = 'img_profile/';
    protected $storagePath = 'img_profile';
    
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('IsActive');
    }
    
    public function updatePassword(Request $request) :RedirectResponse
    {
        
        $rules = [
            'password' => ['required', 'confirmed', Rules\Password::defaults()]
        ];
        
        
        $user_logueado = Auth()->user();
              
        $user_logueado->remember_token = Str::random(60);
        $user_logueado->password = Hash::make($request->password);
        
        $request->validate($rules);//Validator::validate($request->all(), $rules);
        
        $resp = $user_logueado->save();
        
        if (!$resp) {
            Session::flash('error', 'No se pudo actualizar la contraseña. ');
            return redirect('/show/update/acces');
        } 
        
        Session::flash('success_register', 'Contraseña cambiada con exito!!');
        return redirect('/show/update/acces');        
               
    }

    public function editPassword(){
        return view('auth.edit_password');
        
    }

    public function show(Request $request){
        $co_usuario_logueado = Auth()->user()->id;
        
        $this->lastUrl($request);
        
        $sql = "
            SELECT 
                u.co_usuario,
                u.tx_primer_nombre,
                u.tx_segundo_nombre,
                u.tx_primer_apellido,
                u.tx_segundo_apellido,
                u.tx_primer_nombre || ' ' || u.tx_primer_apellido AS full_name,
                u.tx_email,
                u.tx_telefono,
                u.tx_direccion1,
                u.tx_direccion2,
                u.tx_zip,
                u.fe_fecha_creacion,
                u.fe_nac,
                u.co_usuario_padre,
                u.co_sponsor,
                u.co_usuario_reclutador,
                u.co_office_manager,
                u.\"Office_City\" as office_city,
                u.\"Office_City_ID\" as office_city_id,
                u.co_estatus_usuario,
                u.co_tipo_usuario,
                u.co_idioma,    
                COALESCE(r.tx_nombre, 'Sin rol definido') AS tx_rol,    
                usr.image_path as image_path,
                -- Datos del usuario manager
                manager.tx_primer_nombre AS manager_primer_nombre,
                manager.tx_primer_apellido AS manager_primer_apellido,
                manager.tx_primer_nombre || ' ' || manager.tx_primer_apellido AS manager_full_name,
                manager.tx_email AS manager_email,
                manager.tx_telefono AS manager_telefono,
                -- Rol del usuario manager
                COALESCE(r_manager.tx_nombre, 'Sin rol definido') AS rol_manager,
                -- Estatus del usuario
                est.tx_estatus AS estatus_usuario,
                -- Tipo de usuario
                tipo.tx_tipo_usuario AS tipo_usuario,
                -- Idioma
                idioma.tx_idioma AS idioma_usuario
            FROM c002t_usuarios u
            -- Join para obtener el rol del usuario (LEFT JOIN porque puede no tener rol)
            LEFT JOIN c014t_usuarios_roles ur ON u.co_usuario = ur.co_usuario
            LEFT JOIN i007t_roles r ON ur.co_rol = r.co_rol
            -- Join para obtener la foto del usuario
            LEFT JOIN users usr ON u.co_usuario = usr.id
            -- Join para obtener los datos del usuario padre
            LEFT JOIN c002t_usuarios manager ON u.co_usuario_padre = manager.co_usuario
            -- Join para obtener el rol del usuario manager
            LEFT JOIN c014t_usuarios_roles ur_manager ON manager.co_usuario = ur_manager.co_usuario
            LEFT JOIN i007t_roles r_manager ON ur_manager.co_rol = r_manager.co_rol
            -- Joins adicionales para información complementaria
            LEFT JOIN i014t_estatus_usuarios est ON u.co_estatus_usuario = est.co_estatus_usuario
            LEFT JOIN c013t_tipo_usuarios tipo ON u.co_tipo_usuario = tipo.co_tipo_usuario
            LEFT JOIN i012t_idiomas idioma ON u.co_idioma = idioma.co_idioma
            WHERE u.co_usuario = ?
        ";
        $user = DB::select($sql,[$co_usuario_logueado]);
        $user = $user[0];       
        
        return view('auth.edit',['user'=>$user]);
        
    }

    public function update(SettingRequest $request)
    {
        try 
        {
            $user = Auth()->user();
            DB::transaction(function () use ($request, $user) {
                
                // Procesar imagen si existe
                
                if ($request->hasFile('image_profile')) {
                    
                    $url = $this->uploadDocument($request, 'image_profile', $user->image_path);
                    if ($url != null) {
                        Log::info('Se genero la url de la imagen');
                        $user->update([
                            'image_path' => $url
                        ]);                       
                    }
               }
                // Actualizar datos del usuario                
                $user->usuarioEstado->update([
                        'tx_direccion1' => $request->input('first_location'),
                        'tx_direccion2' => $request->input('second_location'),
                        'tx_telefono' => $request->input('phone'),
                        'fe_ultima_mofidicacion' => Carbon::now()
                    ]);
                
                });                
                return redirect()->back()->with('success', "Perfil actualizado");
                
        } catch (\Exception $e) {
            Log::error('Error actualizando perfil: ' . $e->getMessage());
            return redirect()->back()->with('danger', "Error actualizando perfil: " . $e->getMessage());
        }
    }
    
    protected function lastUrl(Request $request)
    {
        $currentUrl = $request->fullUrl();
        $previousUrl = url()->previous();
        if (strcasecmp($currentUrl,$previousUrl)!=0)
             $request->session()->put('previous_url', $previousUrl);
    }

    public function backLastUrl(Request $request)
    {
        return redirect($request->session()->get('previous_url'));
    }

    public function uploadDocument(Request $request, $fieldName,$document)
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
                    $lastName = hash('sha256', Str::random(16) . time());
                    
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
            }else{
                Log::Info('Setting services no hay imagen para actualizar');
                throw new \Exception('Setting services no hay imagen para actualizar');
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
    
}
