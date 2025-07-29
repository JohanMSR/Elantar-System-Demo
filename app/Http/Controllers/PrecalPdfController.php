<?php

namespace App\Http\Controllers;


use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use App\Services\AppCreated;
use App\Services\AppCreated\LastApplicationCreated;

class PrecalPdfController extends Controller
{
    
    public function ver(){
        try{
            $reporte = $this->generarReporte('5588', '3');            
            return $reporte;
        }catch(\Exception $e){
            return $e->getMessage();
        }
        
    }
    public function generarReporte($co_cliente,$typeReport,$pdfPath='',$co_aplicacion='0')
    {
        try {            
            if($co_aplicacion=='0'){
                $objApplication = new LastApplicationCreated();
                $aplicacion = $objApplication->getAplication($co_cliente);                           
                $co_aplicacion = $aplicacion->co_aplicacion;            
            }
            Log::info("102. Codigo de ultima aplicacion", ['co_aplicacion' => $co_aplicacion]);
            $precal = $this->getData($co_aplicacion);
            Log::info("103. Datos de la precalificacion", ['precal' => $precal]);
            $precal->firma_cliente1 = $precal->firma_cliente1 ? $this->getImage($precal->firma_cliente1): null;
            $precal->firma_cliente2 = $precal->firma_cliente2 ? $this->getImage($precal->firma_cliente2): null;
            // Intentar cargar la imagen de múltiples formas
            
            $imagePaths = [
                public_path('img/pdf/logo-encabezado.png'),                
                base_path('public/img/pdf/logo-encabezado.png'),                
            ];
    
            $imageBase64 = null;
            foreach ($imagePaths as $path) {
                if (file_exists($path)) {
                    $imageData = base64_encode(file_get_contents($path));
                    $imageBase64 = 'data:image/png;base64,' . $imageData;
                    break;
                }
            }
    
            if (!$imageBase64) {
                throw new \Exception("No se encontró la imagen en ninguna ruta");
            }
    
            $data = [
                'logoPath' => $imageBase64,
                'imagePath' => $path, // Ruta real de la imagen encontrada
                'precal' => $precal
            ];
            $viewReport = 'dashboard.forms.pdf.reporte-precalificacion';
            $sizeReport = 'Legal';
            if($typeReport!='2'){
                $viewReport = 'dashboard.forms.pdf.reporte-precalificacion-cash';
                $sizeReport = 'A4';
            }
                
            $pdf = Pdf::loadView($viewReport, $data)->setPaper($sizeReport,'Portrait')->setWarnings(false);
            $pdf->setOptions([
            'defaultFont' => 'sans-serif',
            'isHtml5ParserEnabled' => true,
            'enable_images' => true, // Por si acaso no esté ya activado
            'image_resolution' => 150, // Ajusta si es necesario
            'isRemoteEnabled' => true,
            'marginTop' => 10,
            'marginBottom' => 10,
            'marginLeft' => 5,
            'marginRight' => 5,
            ]);
            //            
            $pdfContent = $pdf->output(); // Obtén el contenido del PDF como una cadena
           
            //$path = 'ordenes_trabajo/'.$co_cliente.'.pdf'; // Ruta relativa dentro de storage/app/public
            //Storage::disk('public')->put($path, $pdfContent); // Guarda el PDF en el storage
            // $url = Storage::url($path);
            $path = $pdfPath;
            Storage::disk('public')->put($path, $pdfContent); // Guarda el PDF en el storage
            $url = Storage::url($path);
    
        } catch (\Exception $e) {
            dd($e->getMessage().'  '.$e->getFile().' '.$e->getLine());
        }
        
    }
    
    public function getData($co_aplicacion)
    {
        $sql ="SELECT ap.co_aplicacion, 
            cl.tx_primer_nombre, cl.tx_primer_apellido, cl.nu_documento_id, 
            cl.fe_fecha_nacimiento, cl.tx_licencia, cl.fe_vencimto_lic, cl.tx_telefono,
            CONCAT(
                COALESCE(cl.tx_direccion1, ''),
                ' ',
                COALESCE(cl.tx_direccion2, '') 
            )    AS direccion,
            cl.tx_ciudad, es.tx_nombre AS estado, cl.tx_zip, cl.tx_email,
            cl.tx_nombre_trabajo, cl.nu_tiempo_trabajo, cl.nu_ingreso_principal, 
            cl.tx_cargo, cl.tx_telefono_trabajo,
            cl.tx_direccion1_trabajo, cl.tx_direccion2_trabajo, 
            cl.nu_otros_ingresos, cl.tx_url_img_signature as firma_cliente1,
            cl.tx_ciudad_trab        as tx_ciudad_trab_c1,
            cl.co_estado_trab        as co_estado_trab_c1,

            cl2.tx_primer_nombre AS c2_tx_primer_nombre, 
            cl2.tx_primer_apellido AS c2_tx_primer_apellido, 
            cl2.nu_documento_id AS c2_nu_documento_id, 
            cl2.fe_fecha_nacimiento AS c2_fe_fecha_nacimiento, 
            cl2.tx_licencia AS c2_tx_licencia, cl2.fe_vencimto_lic AS c2_fe_vencimto_lic, 
            cl2.tx_telefono AS c2_tx_telefono, 
            CONCAT(
                COALESCE(cl2.tx_direccion1, ''),
                ' ',
                COALESCE(cl2.tx_direccion2, '')
             )  AS c2_direccion,
            cl2.tx_ciudad AS c2_tx_ciudad, es2.tx_nombre AS c2_estado, 
            cl2.tx_zip AS c2_tx_zip, cl2.tx_email AS c2_tx_email,
            cl2.tx_nombre_trabajo AS c2_tx_nombre_trabajo, 
            cl2.nu_tiempo_trabajo AS c2_nu_tiempo_trabajo, 
            cl2.nu_ingreso_principal AS c2_nu_ingreso_principal, 
            cl2.tx_cargo AS c2_tx_cargo, cl2.tx_telefono_trabajo AS c2_tx_telefono_trabajo, 
            cl2.tx_direccion1_trabajo AS c2_tx_direccion1_trabajo,
            cl2.tx_direccion2_trabajo AS c2_tx_direccion2_trabajo,
            cl2.nu_otros_ingresos AS c2_nu_otros_ingresos, 
            cl2.tx_url_img_signature as firma_cliente2,
            ap.tx_relacion_c2_con_c1,
            ap.tx_hipoteca_estatus, ap.tx_hipoteca_company, ap.nu_hipoteca_renta,
            ap.nu_hipoteca_tiempo,ap.tx_hipoteca_tiempo,
            ap.tx_ref1_nom, ap.tx_ref1_tlf, ap.tx_ref1_rel, ap.tx_ref2_nom, ap.tx_ref2_tlf,
            ap.tx_ref2_rel, ap.fe_creacion as fecha, prod.tx_nombre as producto_promocional,
			ap.nu_cantidad_adultos + ap.nu_cantidad_ninos as personas, 
			agua.tx_tipo_agua as tipo_agua, ap.tx_instalacion as hora_instalacion, 
			metpay.tx_nombre as metodo_de_pago_proyecto, 
			ap.nu_precio_total as precio_total_del_sistema,
			usr1.tx_primer_nombre || ' ' || usr1.tx_primer_apellido as aquafeel_analist,
			ap.nu_cantidad_adultos as cantidad_de_adultos, ap.nu_cantidad_ninos as cantidad_de_ninos,
			cl.tx_url_img_signature  as firma_cliente_principal,
			cl2.tx_url_img_signature as firma_cliente_secundario,            
			ap.tx_nombre_banco, 
            ap.tx_numero_cuenta, 
            ap.tx_numero_ruta, 
            ap.co_tipo_cuenta,
            tipocuenta.tx_tipo_cuenta as tx_tipo_cuenta
	        FROM c001t_aplicaciones AS ap
            JOIN c003t_clientes AS cl ON ap.co_cliente = cl.co_cliente
            LEFT JOIN i010t_estados AS es ON cl.co_estado = es.co_estado
            LEFT JOIN c003t_clientes AS cl2 ON ap.co_cliente2 = cl2.co_cliente
            LEFT JOIN i010t_estados AS es2 ON cl2.co_estado = es2.co_estado
            LEFT JOIN i005t_productos AS prod ON prod.co_producto = ap.co_producto 
            LEFT JOIN i013t_tipos_aguas AS agua ON agua.co_tipo_agua = ap.co_tipo_agua 
            LEFT JOIN i006t_metodos_pagos AS metpay ON metpay.co_metodo_pago = ap.co_metodo_pago_proyecto
            LEFT JOIN c002t_usuarios AS usr1 ON usr1.co_usuario = ap.co_usuario 
            LEFT JOIN public.i025t_tipo_cuenta  as tipocuenta on (tipocuenta.co_tipo_cuenta   = ap.co_tipo_cuenta)    
            WHERE ap.co_aplicacion = $co_aplicacion";
        
        $datos = DB::select($sql);
       
        if(count($datos)>0){
            $datos = $datos[0];
        }    
        return $datos;
    }

    private function getImage($image){
        
        $imagePath = storage_path('app/public' . $image);
            // Verificar si la imagen existe
            if (file_exists($imagePath)) {
                //dd($imagePath);
                $imageData = base64_encode(file_get_contents($imagePath));
                $imageBase64 = 'data:image/png;base64,' . $imageData; // Cambia 'png' por el formato correcto si es necesario
            } else {
                $imageBase64 = null; // Manejar el caso donde la imagen no existe
            }
        return $imageBase64;
    }    

}