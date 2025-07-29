<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Http\Requests\StorePrecalificacionRequest;
use Illuminate\Support\Facades\Storage;
use App\Models\C040tBasePrecalificacion;
use App\Models\I006tMetodosPago;
use App\Models\I005tProducto;
use App\Models\I013tTiposAgua;
use App\Models\C003tCliente;
use App\Models\I010tEstado;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use App\Jobs\GenerarReporte;
use App\Services\Emails\UserEmailAppCreated;
use App\Models\I015tEstatusHipoteca;
use App\Http\Requests\UpdatePrecalificacionRequest;
use App\Services\Dashboard\TeamDetailService;
use App\Jobs\SendEmailWithPDF;
use App\Jobs\EmailAppCreated;
use App\Models\I025tTipoCuenta;
use Illuminate\Support\Str;

class GeneralInfoController extends Controller
{
    public function create(Request $request)
    {
        try {
            $metodosPago = I006tMetodosPago::all();
            $productosPromotions = I005tProducto::where('co_tipo_producto', 2)->get();
            $productos = I005tProducto::where('co_tipo_producto', 1)->get();
            $tiposAgua = I013tTiposAgua::all();
            $estados = I010tEstado::all();
            $hipotecas = I015tEstatusHipoteca::all();
            $tiposCuenta = I025tTipoCuenta::all();
            // Obtener datos del cliente si existe el código
            $cliente = null;
            if ($request->has('co_cliente')) {
                $cliente = C003tCliente::where('co_cliente', $request->co_cliente)->first();
              
                if (!$cliente) {
                    return view('dashboard.forms.general-info')
                        ->with('error_f', 'Cliente no encontrado');
                }
            }
            
            return view('dashboard.forms.general-info', [
                'metodosPago' => $metodosPago,
                'productosPromotions' => $productosPromotions,
                'productos' => $productos,
                'tiposAgua' => $tiposAgua,
                'cliente' => $cliente,
                'estados' => $estados,
                'hipotecas' => $hipotecas,
                'tiposCuenta' => $tiposCuenta
            ]);
        } catch (\Exception $e) {
            return view('dashboard.forms.general-info')
            ->with('error_f', 'Error al cargar los datos necesarios');            
        }
    }

    private function processImage($request, $fieldName, $type = 'photo')
    {
        $rutaDestino = '';
        try {
            if ($type === 'signature') {
                if($request->has($fieldName) && !empty($request->input($fieldName))){
                    $image = $request->input($fieldName);
                    list($type, $data) = explode(';', $image);
                    list(, $data) = explode(',', $data);
                    $imageData = base64_decode($data);
                    $extension = '.png';
                    $nombre_temp = $fieldName.'_'.uniqid().'_'.time().$extension;
                    $path = 'img/firma_cliente/'.$nombre_temp;
                    $manager = new ImageManager(new Driver()); // o 'imagick'
                    $imagex = $manager->read($imageData);

                    // Obtener las dimensiones originales de la imagen
                    $originalWidth = $imagex->width();
                    $originalHeight = $imagex->height();

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
                    $imagex->resize($width, $height);

                    // Guardar la imagen
                    Storage::disk('public')->put($path, $imagex->encode());
                    $url = '/'.$path;
                }else{
                    throw new \Exception('La firma no se ha cargado...');
                }

            } elseif ($type === 'photo' && $request->hasFile($fieldName)) {
                if ($request->hasFile($fieldName) && $request->file($fieldName)->isValid()) 
                {
                    $file = $request->file($fieldName);
                    $rutaDestino = storage_path('app/public/temp/' . $file->getClientOriginalName());
                    $file->move(dirname($rutaDestino), basename($rutaDestino));
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

                    $nombre_temp = $fieldName.'_'.uniqid().'_'.time().'.'.$file->getClientOriginalExtension();
                    $path = 'img/foto_id/'.$nombre_temp;
                    Storage::disk('public')->put($path, $image->encode());
                    $url = '/'.$path;
                    //Borra el archivo temporal
                    if (file_exists($rutaDestino)) {
                        unlink($rutaDestino);
                    }
                }
                else
                {
                    throw new \Exception('El archivo no es válido.');
                }    
                
            } else {
                return null;
            }
            
            return $url;
    
        } catch (\Exception $e) {
            //Log::error('5. Error procesando imagen: ' . $e->getMessage().' '.$e->getFile());
            return null;            
        }       
    }

    public function store(StorePrecalificacionRequest $request)
    {
        try {
            $validatedData = $request->validated();
            $validatedData['fe_registro'] = Carbon::now();            
            // Procesar todas las imágenes
            $imageFields = [
                'tx_url_img_photoid_c1' => 'photo',
                'tx_url_img_photoid_c2' => 'photo',
                'tx_url_img_signature_c1' => 'signature',
                'tx_url_img_signature_c2' => 'signature'
            ];
            Log::info('1. Leer las firma e imagenes');
            foreach ($imageFields as $field => $type) {
                if ($path = $this->processImage($request, $field, $type)) {
                    $validatedData[$field] = $path;
                }
            }
            $validatedData['co_usuario_log'] = auth()->id();                        
            Log::info('2. Iniciar la creacion de precalificacion');
            DB::beginTransaction();
                try {
                    if (isset($validatedData['tx_social_security_number_c1']) && !empty($validatedData['tx_social_security_number_c1'])) {
                        $affected = DB::update("UPDATE c003t_clientes as cl1 SET 
                            bo_tipo_cliente = ?                            
                            WHERE cl1.co_cliente = ?", 
                            ['t', $request->co_cliente]);
                        $socialSecurityNumber = $validatedData['tx_social_security_number_c1'];    
                        $sql = "SELECT co_cliente 
                                FROM c003t_clientes 
                                WHERE nu_documento_id =  '$socialSecurityNumber' and nu_documento_id <> '000000000'  
                                ORDER BY c003t_clientes.fe_ultima_act DESC LIMIT 1
                                ";
                        $clienteActual = DB::select($sql);
                        if(count($clienteActual)> 0){                     
                           $request->merge(['co_cliente' => $clienteActual[0]->co_cliente]);
                           $validatedData['co_cliente'] = $clienteActual[0]->co_cliente;
                        }                           
                    }
                    $fecha_actual = Carbon::now()->format('mdYHis');
                    $validatedData['tx_url_orden_trabajo'] ='/'.'ordenes_trabajo/'.$request->co_cliente.$fecha_actual.'.pdf';
                    $precalificacion = C040tBasePrecalificacion::create($validatedData);
                    if(!$precalificacion){
                        throw new \Exception('Error al crear la precalificacion');
                    }
                      
                } catch (\Illuminate\Database\QueryException $e) {
                    throw new \Exception($e->getMessage()); 
                }    
            DB::commit();
            Log::info('3. Antes de lanzar el PDF');
           
            $pdfPath = $validatedData['tx_url_orden_trabajo'];
            $pdfPath_reporte = Str::substr($pdfPath, 1);
            
            GenerarReporte::dispatch($request->co_cliente, $request->co_metodo_pago_proyecto,$pdfPath_reporte)
            ->delay(now()->addSeconds(6))
            ->chain([               
                new SendEmailWithPDF($request->co_cliente, $pdfPath)
            ]);
            Log::info('4. Se genero el PDF');            

            return redirect()->route('shop')
            ->with('success_register', 'Precalificación Creada exitosamente');

        } catch (\Exception $e) {            
            Log::info("Warning Creando Precalificacion: ".$e->getMessage());
            redirect()->route('shop')
                ->with('error_f', 'Se produjo un error creando la Precalificación');
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request, $urldestination='account')
    {
       
        try {

            try {
                $validated = $request->validate([
                    'co_aplicacion' => 'required|numeric'
                ]);
            } catch (\Illuminate\Validation\ValidationException $e) {
                return view('dashboard.forms.edit-precalificacion')
                ->with('error_f', 'El codigo de la aplicacion es requerido y debe ser un numero entero');
            }
            $metodosPago = I006tMetodosPago::all();
            $productosPromotions = I005tProducto::where('co_tipo_producto', 2)->get();
            $productos = I005tProducto::where('co_tipo_producto', 1)->get();
            $tiposAgua = I013tTiposAgua::all();
            $estados = I010tEstado::all();
            $hipotecas = I015tEstatusHipoteca::all();
            $tiposCuenta = I025tTipoCuenta::all();
            
            $co_aplicacion = $validated['co_aplicacion'];
            $services = new TeamDetailService();
            $aplicacion = $services->getData($co_aplicacion);  
                          
            if (!$aplicacion) {
                return view('dashboard.forms.edit-precalificacion')
                ->with('error_f', 'Aplicacion no encontrada');
            }
            
            
            return view('dashboard.forms.edit-precalificacion', [
                'metodosPago' => $metodosPago,
                'productosPromotions' => $productosPromotions,
                'productos' => $productos,
                'tiposAgua' => $tiposAgua,
                'aplicacion' => $aplicacion,
                'estados' => $estados,
                'hipotecas' => $hipotecas,
                'urldestination' => $urldestination,
                'tiposCuenta' => $tiposCuenta
            ]);
        } catch (\Exception $e) {
            return view('dashboard.forms.edit-precalificacion')
            ->with('error_f', 'Error al cargar los datos necesarios');            
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePrecalificacionRequest $request)
    {
        $urlDestination = (!empty($request->urldestination) && $request->urldestination !== 'account') 
        ? 'dashboard.team' 
        : 'account';
        
        try {
            $validatedData = $request->validated();
            $validatedData['fe_registro'] = Carbon::now();
            
            // Procesar todas las imágenes
            $imageFields = [
                'tx_url_img_photoid_c1' => 'photo',
                'tx_url_img_photoid_c2' => 'photo',
                'tx_url_img_signature_c1' => 'signature',
                'tx_url_img_signature_c2' => 'signature'
            ];
            Log::info('1. Leer las firma e imagenes');
            $co_aplicacion = $validatedData['co_aplicacion'];
            
            $tx_url_img_signature_c1_old = $validatedData['tx_url_img_signature_c1_old '] ?? '';
            $tx_url_img_signature_c2_old = $validatedData['tx_url_img_signature_c2_old '] ?? '';
            
            foreach ($imageFields as $field => $type) {
                $isSignatureC1Changed = $field === 'tx_url_img_signature_c1' && 
                    $tx_url_img_signature_c1_old !== $validatedData['tx_url_img_signature_c1'];
    
                $isSignatureC2Changed = $field === 'tx_url_img_signature_c2' && 
                    $tx_url_img_signature_c2_old !== $validatedData['tx_url_img_signature_c2'];
                $isPhotoC1Changed = true;
                $isPhotoC2Changed = true;
                if (!isset($validatedData['tx_url_img_photoid_c1'])) {
                    
                    $validatedData['tx_url_img_photoid_c1'] = $validatedData['tx_url_img_photoid_c1_old'];
                    
                    $isPhotoC1Changed = false;
                }
                if (!isset($validatedData['tx_url_img_photoid_c2'])) {
                    $validatedData['tx_url_img_photoid_c2'] = $validatedData['tx_url_img_photoid_c2_old'];
                    $isPhotoC1Changed = false;
                }
                
                if ($type === 'signature' && ($isSignatureC1Changed || $isSignatureC2Changed)) {
                    if ($path = $this->processImage($request, $field, $type)) {
                        $validatedData[$field] = $path;
                    }
                }
                if ($type === 'photo' && ($isPhotoC1Changed || $isPhotoC2Changed)) {
                    if ($path = $this->processImage($request, $field, $type)) {
                        $validatedData[$field] = $path;
                    }
                }
                
            }
            //Eliminar los campos ocultos
            $keysToRemove = [
                'tx_url_img_photoid_c2_old' => '',
                'tx_url_img_photoid_c1_old' => '',
                'tx_url_img_signature_c1_old' => '',
                'tx_url_img_signature_c2_old' => '',
                'urldestination' => ''
            ];
            $validatedData = array_diff_key($validatedData, $keysToRemove);
            $validatedData['co_usuario_log'] = auth()->id();            
            
            
            Log::info('2. Iniciar la creacion de precalificacion');
            
            DB::beginTransaction();
                try {
                    
                    $precalificacion = C040tBasePrecalificacion::create($validatedData);
                    if(!$precalificacion){
                        throw new \Exception('Error al crear la precalificacion');
                    }
                   
                } catch (\Illuminate\Database\QueryException $e) {
                    throw new \Exception($e->getMessage()); 
                }    
            DB::commit();
            
            Log::info('3. Antes de lanzar el PDF');
            //Borrar el PDF existente
            if (Storage::disk('public')->exists($validatedData['tx_url_orden_trabajo'])) {
                Storage::disk('public')->delete($validatedData['tx_url_orden_trabajo']);
            }
            
            $pdfPath = $validatedData['tx_url_orden_trabajo'];
            $pdfPath_reporte = Str::substr($pdfPath, 1);
            GenerarReporte::dispatch($request->co_cliente, $request->co_metodo_pago_proyecto,$pdfPath_reporte, $request->co_aplicacion)
            ->delay(now()->addSeconds(6))
            ->chain([               
                new SendEmailWithPDF($request->co_cliente, $pdfPath)
            ]);
            Log::info('4. Se genero el PDF');                       
            return redirect()->route($urlDestination)
            ->with('success_register', 'Aplicación Actualizada Exitosamente');

        } catch (\Exception $e) {            
            
            Log::info("Warning Actualizando Aplicación: ".$e->getMessage());
            redirect()->route($urlDestination)
                ->with('error_f', 'Se produjo un error actualizando la Aplicacion');
        }
    }

} 