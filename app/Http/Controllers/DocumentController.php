<?php

namespace App\Http\Controllers;

use App\Services\Documents\DocumentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\DocumentAppRequest;
use Exception;
use Illuminate\Validation\ValidationException;

class DocumentController extends Controller
{
    public function index(Request $request){
        
        $validatedData = $request->validate([
            'co_aplicacion' => 'required|integer',
        ], [
            'co_aplicacion.required' => 'El código de la aplicación es requerido.',
            'co_aplicacion.integer' => 'El código de la aplicación debe ser un valor entero.',
        ]);

        $fileFields = [
            'tx_url_img_compago1',
            'tx_url_img_compago2',
            'tx_url_img_compago3',
            'tx_url_img_checknull',
            'tx_url_img_compropiedad',
            'tx_url_img_declaraimpuesto',
            'tx_url_img_otro',
        ];
        // Obtener el valor de co_aplicacion
        $co_aplicacion = $validatedData['co_aplicacion'];

        // Realizar la consulta para obtener los datos de la aplicación
        $result = DB::select("SELECT * FROM c001t_aplicaciones WHERE co_aplicacion = ?", [$co_aplicacion]);

        // Retornar los datos en formato JSON
        if (!empty($result)) {
            $result = $result[0];            
            return response()->json(['success' => true, 'data' => $result], 200);
        } else {
            return response()->json(['success' => false, 'message' => 'No se encontraron datos para la aplicación especificada.'], 404);
        } 


    }
    public function store(Request $request, DocumentService $documentService)
    {
        $validatedData = $request->validate([
            'tx_url_img_compago1' => 'nullable|file|max:3072|mimes:jpeg,png,jpg,gif,webp,pdf',
            'tx_url_img_compago2' => 'nullable|file|max:3072|mimes:jpeg,png,jpg,gif,webp,pdf',
            'tx_url_img_compago3' => 'nullable|file|max:3072|mimes:jpeg,png,jpg,gif,webp,pdf',
            'tx_url_img_checknull' => 'nullable|file|max:3072|mimes:jpeg,png,jpg,gif,webp,pdf',
            'tx_url_img_compropiedad' => 'nullable|file|max:3072|mimes:jpeg,png,jpg,gif,webp,pdf',
            'tx_url_img_declaraimpuesto' => 'nullable|file|max:3072|mimes:jpeg,png,jpg,gif,webp,pdf',
            'tx_url_img_otro' => 'nullable|file|max:3072|mimes:jpeg,png,jpg,gif,webp,pdf',
        ], [
            'tx_url_img_compago1.file' => 'El archivo de Comprobante de pago 1 debe ser un archivo válido.',
            'tx_url_img_compago1.max' => 'El archivo de Comprobante de pago 1 no debe exceder los 3 MB.',
            'tx_url_img_compago1.mimes' => 'El archivo de Comprobante de pago 1 debe ser un tipo de archivo: jpeg, png, jpg, gif, webp, pdf.',
            
            'tx_url_img_compago2.file' => 'El archivo de Comprobante de pago 2 debe ser un archivo válido.',
            'tx_url_img_compago2.max' => 'El archivo de Comprobante de pago 2 no debe exceder los 3 MB.',
            'tx_url_img_compago2.mimes' => 'El archivo de Comprobante de pago 2 debe ser un tipo de archivo: jpeg, png, jpg, gif, webp, pdf.',
            
            'tx_url_img_compago3.file' => 'El archivo de Comprobante de pago 3 debe ser un archivo válido.',
            'tx_url_img_compago3.max' => 'El archivo de Comprobante de pago 3 no debe exceder los 3 MB.',
            'tx_url_img_compago3.mimes' => 'El archivo de Comprobante de pago 3 debe ser un tipo de archivo: jpeg, png, jpg, gif, webp, pdf.',
            
            'tx_url_img_checknull.file' => 'El archivo de Check Anulado debe ser un archivo válido.',
            'tx_url_img_checknull.max' => 'El archivo de Check Anulado no debe exceder los 3 MB.',
            'tx_url_img_checknull.mimes' => 'El archivo de Check Anulado debe ser un tipo de archivo: jpeg, png, jpg, gif, webp, pdf.',
            
            'tx_url_img_compropiedad.file' => 'El archivo de Comprobante de propiedad debe ser un archivo válido.',
            'tx_url_img_compropiedad.max' => 'El archivo de Comprobante de propiedad no debe exceder los 3 MB.',
            'tx_url_img_compropiedad.mimes' => 'El archivo de Comprobante de propiedad debe ser un tipo de archivo: jpeg, png, jpg, gif, webp, pdf.',
            
            'tx_url_img_declaraimpuesto.file' => 'El archivo de Declaracion de Impuesto debe ser un archivo válido.',
            'tx_url_img_declaraimpuesto.max' => 'El archivo de Declaracion de  Impuesto no debe exceder los 3 MB.',
            'tx_url_img_declaraimpuesto.mimes' => 'El archivo de Declaracion de Impuesto debe ser un tipo de archivo: jpeg, png, jpg, gif, webp, pdf.',

            'tx_url_img_otro.file' => 'El archivo de Declaracion de Impuesto debe ser un archivo válido.',
            'tx_url_img_otro.max' => 'El archivo de Declaracion de  Impuesto no debe exceder los 3 MB.',
            'tx_url_img_otro.mimes' => 'El archivo de Declaracion de Impuesto debe ser un tipo de archivo: jpeg, png, jpg, gif, webp, pdf.',
        ]);
    
        $fileFields = [
            'tx_url_img_compago1',
            'tx_url_img_compago2',
            'tx_url_img_compago3',
            'tx_url_img_checknull',
            'tx_url_img_compropiedad',
            'tx_url_img_declaraimpuesto',
            'tx_url_img_otro',
        ];
        
        $co_aplicacion = $request->input('co_aplicacion');
        $valores = [];
        $fieldBD = [];
        try {
            
            // Realizar la consulta para obtener los datos de la aplicación
            $result = DB::select("SELECT * FROM c001t_aplicaciones WHERE co_aplicacion = ?", [$co_aplicacion]);
            
            if (!empty($result)) {                
                $result = $result[0];
            }    
        
                       
            foreach ($fileFields as $field) {                
                $document = $result->$field ? $result->$field : null;
                $url = $documentService->uploadDocument($request, $field,$co_aplicacion,$document);
                if (!is_null($url)) {
                    $fieldBD[]  = "$field = ?";
                    $valores[] = $url;
                }
            }    
            if (count($fieldBD) > 0) {
                // Construir la consulta
                $query = "UPDATE c001t_aplicaciones as ap SET " . implode(", ", $fieldBD) . " WHERE ap.co_aplicacion = ?";
                $valores[] = $co_aplicacion; // Agregar el valor de co_aplicacion al final
    
                // Ejecutar la consulta            
                $affected = DB::update($query, $valores);

                if($affected > 0)
                {
                   return response()->json(['message' => 'Documentos Cargados exitosamente en la aplicacion '.$co_aplicacion], 201);                    
                }
            }else{                    
                return response()->json(['message' => 'Documentos Cargados exitosamente en la aplicacion '.$co_aplicacion], 201);        

            }        
            

        } catch (ValidationException $e) {
            return response()->json(['error' => $e->getMessage()], 422);
                
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);            
        }   
    }

    public function storeContract(Request $request, DocumentService $documentService)
    {
        $validatedData = $request->validate([
            'tx_url_img_contract' => 'nullable|file|max:4096|mimes:jpeg,png,jpg,gif,webp,pdf',            
        ], [
            'tx_url_img_contract.file' => 'El archivo del Contrato debe ser un archivo válido.',
            'tx_url_img_contract.max' => 'El archivo del Contrato no debe exceder los 4 MB.',
            'tx_url_img_contract.mimes' => 'El archivo del Contrato debe ser un tipo de archivo: jpeg, png, jpg, gif, webp, pdf.',
        ]);    
        
        
        $co_aplicacion = $request->input('co_aplicacion');
        
        try {
            
            // Realizar la consulta para obtener los datos de la aplicación
            $result = DB::select("SELECT * FROM c001t_aplicaciones WHERE co_aplicacion = ?", [$co_aplicacion]);
            
            if (!empty($result)) {                
                $result = $result[0];
            }    
            $document = $result->tx_url_img_contract ? $result->tx_url_img_contract  : null;
            $url = $documentService->uploadDocument($request, 'tx_url_img_contract',$co_aplicacion,$document);
            if (!is_null($url)) {
                $query = "UPDATE c001t_aplicaciones SET tx_url_img_contract = ? WHERE co_aplicacion = ?";
                $valores = [$url,$co_aplicacion];
                $affected = DB::update($query, $valores);

                if($affected > 0)
                {
                   //return response()->json(['message' => 'Documentos Cargados exitosamente en la aplicacion '.$co_aplicacion], 201);                    
                   return redirect()->route('dashboard.team-details', ['co_aplicacion' => $co_aplicacion])
                    ->with('success_register', 'Aplicación #' . $co_aplicacion . ' Contrato actualizado correctamente')
                    ->with('tabActive', 'documentos-tab');
                }  
            }else{                    
                   // return response()->json(['message' => 'Documento de  Contrato no Cargado la aplicacion '.$co_aplicacion], 201);
                   return redirect()->back()
                    ->with('error_f','Aplicacion #'. $co_aplicacion.' Contrato no Cargado');
            }        
        }catch (ValidationException $e) {
           // return response()->json(['error' => $e->getMessage()], 422);
           return redirect()->back()
           ->with('error_f','Aplicacion #'. $co_aplicacion.' '.$e->getMessage());
                
        } catch (\Exception $e) {
            //return response()->json(['error' => $e->getMessage()], 500);            
            return redirect()->back()
            ->with('error_f','Aplicacion #'. $co_aplicacion.' '.$e->getMessage());
        }   
    }
}
