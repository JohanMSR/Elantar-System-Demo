<?php

namespace App\Http\Controllers\Dashboard;
use App\Http\Requests\CodigoApplicationRequest;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use App\Http\Requests\UpdateFinancialStatusRequest;
use App\Models\I002tEstatusFinanciero;
use Illuminate\Support\Facades\Session;
use App\Jobs\GenerarReporte;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use App\Models\I018tFinanciera;
use App\Models\I019tTipoFinanciamiento;
use App\Http\Requests\FinancieraRequest;
class TeamDetailsController extends Controller
{
    public function showTeamData(Request $request)
    {
        $co_usuario_logueado = Auth()->id();
        $application =$this->getDetailsApplication($request);                

        $status_financial = $this->getStatusFinanciero($application->metodo_de_pago);                           
        
                
        if(!empty($application) && is_object($application)) {
            
            $logs = $this->getApplicationLogs($application->co_aplicacion);
            $notificacionesActualizadas = $this->updateNotificationApp($co_usuario_logueado, $application->co_aplicacion);
            if(!$notificacionesActualizadas){
                return redirect()->back()
                ->with('error_f','No se pudieron actualizar las notificaciones');
            }
            $financieras = I018tFinanciera::all();
            $tipos_financiamiento = I019tTipoFinanciamiento::all();
            //Actualizamos las notificaciones
                        
            return view('dashboard.team.team-details')
                ->with('app', $application)
                ->with('status_financial', $status_financial)
                ->with('logs', $logs)
                ->with('financieras', $financieras)
                ->with('tipos_financiamiento', $tipos_financiamiento);
        }
        
        return redirect()->route('dashboard.team')
        ->with('error_f', 'Aplicación no encontrada');
        
    }

    public function getDetailsApplication(Request $request){
        
        $request->validate([
            'co_aplicacion' => 'required|integer',
        ], [
            'co_aplicacion.required' => 'El código de aplicación es obligatorio.',
            'co_aplicacion.integer' => 'El código de aplicación debe ser un número entero.',
        ]);
        
        $codigo = $request->query('co_aplicacion');
        $sql ="
            SELECT 
            ap1.co_qb_id_proyecto, 
            ap1.co_aplicacion,
            edoap.co_estatus_aplicacion as codigo_estatus_actual,            
            edoap.tx_nombre as etapa_actual,
            edoap.in_orden  as orden_actual,
            edoapsig.tx_nombre as siguiente_etapa,
            edoapsig.in_orden  as siguiente_orden,

            edoapf.co_estatus_financiero as codigo_estatus_financiero,
            edoapf.tx_nombre as f_etapa_actual,
            edoapf.in_orden  as f_orden_actual,
            edoapfsig.tx_nombre as f_siguiente_etapa,
            edoapfsig.in_orden  as f_siguiente_orden,

            c1.co_cliente as cp_codigo_cliente,
            c1.tx_primer_nombre || ' ' || c1.tx_primer_apellido as Cliente_principal_nom,
            c1.tx_telefono as Cliente_principal_tlf,
            c1.tx_email as Cliente_principal_email,
            COALESCE(c1.tx_direccion1, ' ') || ', ' || COALESCE(c1.tx_direccion2, ' ') ||  ', ' || COALESCE(c1.tx_ciudad, ' ')  ||  ', ' || COALESCE(est1.tx_nombre, ' ') ||  ', ' || COALESCE(c1.tx_zip, ' ') as Cliente_principal_dir,
            ap1.tx_idioma as Primary_Language,
            mpayp.tx_nombre as Metodo_de_Pago_del_Proyecto,
            ap1.nu_precio_total as Precio_total,
            ap1.nu_monto_inicial as Down_Payment,
            (ap1.nu_precio_total-COALESCE(ap1.nu_monto_inicial, 0)) as Total_financiado,
            tipoagua.tx_tipo_agua as IS_Water_source,
            promo.tx_nombre as Promotions,

                -- Informacion del analista
            u1.tx_primer_nombre || ' ' || u1.tx_primer_apellido as ap_nombre,
            u1.co_usuario  as ap_ID,
            u1.tx_telefono as ap_telefono,
            u1.tx_email as ap_email,
            u3.tx_primer_nombre || ' ' || u3.tx_primer_apellido as ap_gerente,
            u3.tx_telefono as ap_gerente_tlf,

            u2.tx_primer_nombre || ' ' || u2.tx_primer_apellido as as_nombre,
            u2.co_usuario  as as_ID,
            u2.tx_telefono as as_telefono,
            u2.tx_email as as_email,

                -- Informacion General  -- Detalle del pago
            mpayp.tx_nombre      as metodo_de_pago,
            ap1.nu_monto_inicial as down_payment,
            ap1.nu_precio_total  as precio_total_del_sistema,
            (ap1.nu_precio_total - ap1.nu_monto_inicial) as importe_total_financiado,

                -- Detalle del pago
            ap1.nu_cantidad_adultos as adultos_en_casa,
            ap1.nu_cantidad_ninos   as ninos_en_casa,
            promo.tx_nombre         as promociones_incluidas,

                -- Cliente principal
            idioma.tx_idioma      as cp_idioma_principal,
            c1.tx_primer_nombre   as cp_primer_nombre,
            c1.tx_segundo_nombre  as cp_inicial_seg,
            c1.tx_primer_apellido as cp_primer_apel,
            c1.tx_telefono        as cp_telefono,
            c1.tx_email           as cp_correo,
            c1.tx_direccion1      as cp_direccion1,
            c1.tx_direccion2   as cp_direccion2,
            c1.tx_ciudad as  cp_ciudad,
            est1.tx_nombre as cp_estado,
            c1.tx_zip as cp_zip,
            TRIM(TRAILING ', ' FROM 
                        COALESCE(c1.tx_direccion1 || ', ', '') ||
                        COALESCE(c1.tx_direccion2 || ', ', '') ||
                        COALESCE(c1.tx_ciudad     || ', ', '') ||
                        COALESCE(est1.tx_nombre   || ', ', '') ||
                        COALESCE(c1.tx_zip, '')
             ) AS cp_direc,
             c1.fe_fecha_nacimiento as cp_fe_fecha_nacimiento,
             c1.nu_documento_id as cp_social_security_number,
             c1.tx_licencia as cp_licencia,
             c1.fe_vencimto_lic as cp_vencimto_lic,
             c1.tx_nombre_trabajo  as cp_empleador,
             c1.tx_cargo as cp_cargo,
             c1.tx_telefono_trabajo   as cp_tlf_trabajo,
             c1.tx_direccion1_trabajo as cp_dir_trabajo,
             c1.tx_direccion2_trabajo as cp_dir2_trabajo,

             c1.nu_tiempo_trabajo as cp_tiempo_trabajo,
             c1.nu_ingreso_principal as cp_ingreso_principal,


             c1.nu_otros_ingresos     as cp_ingreso_alterno,
             ap1.bo_co_signer as hay_un_cofirmante,

                -- Cliente Secundario
             idioma.tx_idioma      as cs_idioma_principal,
             c2.co_cliente         as cs_codigo_cliente,
             c2.tx_primer_nombre   as cs_primer_nombre,
             c2.tx_segundo_nombre  as cs_inicial_seg,
             c2.tx_primer_apellido as cs_primer_apel,
             c2.tx_telefono        as cs_telefono,
             c2.tx_email           as cs_correo,
             c2.tx_direccion1 as cs_direccion1,
             c2.tx_direccion2 as cs_direccion2,
             c2.tx_ciudad as cs_ciudad,
             est1.tx_nombre as cs_estado,
             c2.tx_zip as cs_zip,
             TRIM(TRAILING ', ' FROM 
                        COALESCE(c2.tx_direccion1 || ', ', '') ||
                        COALESCE(c2.tx_direccion2 || ', ', '') ||
                        COALESCE(c2.tx_ciudad     || ', ', '') ||
                        COALESCE(est1.tx_nombre   || ', ', '') ||
                        COALESCE(c2.tx_zip, '')
                ) AS cs_direc,
                c2.fe_fecha_nacimiento as cs_fe_fecha_nacimiento,
                c2.nu_documento_id as cs_social_security_number,
                c2.tx_licencia as cs_licencia,
                c2.fe_vencimto_lic as cs_vencimto_lic,    
                c2.tx_nombre_trabajo     as cs_empleador,
                c2.tx_cargo              as cs_cargo,
                c2.tx_telefono_trabajo   as cs_tlf_trabajo,
                c2.tx_direccion1_trabajo as cs_dir_trabajo,
                c2.tx_direccion2_trabajo as cs_dir2_trabajo,
                c2.nu_tiempo_trabajo as cs_tiempo_trabajo,
                c2.nu_ingreso_principal as cs_ingreso_principal,
                c2.nu_otros_ingresos     as cs_ingreso_alterno,

                -- Informacion de la hipoteca
                ap1.tx_hipoteca_estatus as estatus_hipoteca,
                ap1.tx_hipoteca_company as company_hipoteca,
                ap1.nu_hipoteca_renta   as renta_hipoteca,
                ap1.tx_hipoteca_tiempo  as tiempo_hipoteca,

                -- Referencias
                ap1.tx_ref1_nom as nombre_ref1,
                ap1.tx_ref1_tlf as telefono_ref1,
                ap1.tx_ref1_rel as relacion_ref1,

                ap1.tx_ref2_nom as nombre_ref2,
                ap1.tx_ref2_tlf as telefono_ref2,
                ap1.tx_ref2_rel as relacion_ref2,

                -- Instalacion
                ap1.fe_instalacion as fecha_estimada,
                ap1.tx_instalacion as tiempo_estimado,
                ap1.tx_tipo_agua   as tipo_de_agua,

                -- Documentos
                ap1.tx_url_orden_trabajo  as orden_de_trabajo,
                c1.tx_url_img_signature as id_cliente_principal,
                c1.tx_url_img_photoid   as fotoid_cliente1,
                c2.tx_url_img_signature as id_cliente_secundario,
                c2.tx_url_img_photoid   as fotoid_cliente2,

                ap1.tx_url_img_compago1 as comprobante_pago1,
                ap1.tx_url_img_compago2 as comprobante_pago2,
                ap1.tx_url_img_compago3 as comprobante_pago3,
                ap1.tx_url_img_checknull as comprobante_cheque_nulo,
                ap1.tx_url_img_compropiedad as comprobante_propiedad,
                ap1.tx_url_img_declaraimpuesto as comprobante_impuesto,
                ap1.tx_url_img_otro as comprobante_otro_documento,
                ap1.tx_url_img_contract as contrato,
                
                ap1.tx_nombre_banco,
                ap1.tx_numero_cuenta,
                ap1.tx_numero_ruta,
                ap1.co_tipo_cuenta,
                ap1.tx_titular_cuenta,
                
                tipocuenta.tx_tipo_cuenta as tx_tipo_cuenta,
                ap1.co_financiera,
                ap1.co_tipo_financiamiento,
                ap1.tx_rango,                
                ap1.tx_meses,
                ap1.nu_porcentajeap,
                ap1.nu_tasa_interes,
                ap1.nu_pago_mensual,                                
                financiera.tx_nombre as tx_financiera,
                tipo_financiamiento.tx_nombre as tx_financiamiento
                
                FROM c001t_aplicaciones as ap1 
                INNER JOIN public.c003t_clientes as c1 on (c1.co_cliente  = ap1.co_cliente) 
                LEFT JOIN public.i010t_estados as est1 on (est1.co_estado = c1.co_estado) 
                LEFT JOIN public.c002t_usuarios as u1 on (u1.co_usuario  = ap1.co_usuario) 
                LEFT JOIN public.c002t_usuarios as u2 on (u2.co_usuario  = ap1.co_usuario_2) 
                LEFT JOIN public.c002t_usuarios as u3 on (u3.co_usuario  = u1.co_office_manager) 
                LEFT JOIN public.i001t_estatus_aplicaciones as edoap on (edoap.co_estatus_aplicacion  = ap1.co_estatus_aplicacion) 
                LEFT JOIN public.i001t_estatus_aplicaciones as edoapsig on (edoapsig.co_estatus_aplicacion  = edoap.co_estatus_siguiente) 

                LEFT JOIN public.i002t_estatus_financieros as edoapf on (edoapf.co_estatus_financiero  = ap1.co_estatus_financiero) 
                LEFT JOIN public.i002t_estatus_financieros as edoapfsig on (edoapfsig.co_estatus_financiero  = edoapf.co_estatus_siguiente) 

                LEFT JOIN public.c003t_clientes as c2 on (c2.co_cliente  = ap1.co_cliente2) 
                LEFT JOIN public.i006t_metodos_pagos as mpayp on (mpayp.co_metodo_pago  = ap1.co_metodo_pago_proyecto) 
                LEFT JOIN public.i013t_tipos_aguas as tipoagua on (tipoagua.co_tipo_agua  = ap1.co_tipo_agua)
                LEFT JOIN public.i005t_productos as promo  on (promo.co_producto  = ap1.co_producto)
                LEFT JOIN public.i012t_idiomas   as idioma on (idioma.co_idioma   = ap1.co_idioma)
                LEFT JOIN public.i025t_tipo_cuenta   as tipocuenta on (tipocuenta.co_tipo_cuenta = ap1.co_tipo_cuenta)
                LEFT JOIN public.i018t_financiera as financiera on (financiera.co_financiera = ap1.co_financiera)
                LEFT JOIN public.i019t_tipo_financiamiento as tipo_financiamiento on (tipo_financiamiento.co_tipo_financiamiento = ap1.co_tipo_financiamiento)
                where ap1.co_aplicacion = $codigo"; 
            $datos = DB::select($sql);
            if(count($datos)> 0){
                $datos = $datos[0];
            }
            return $datos;

    }
    public function nextStateApp(Request $request){
        
        $request->validate([
            'co_aplicacion' => 'required|integer',
        ], [
            'co_aplicacion.required' => 'El código de aplicación es obligatorio.',
            'co_aplicacion.integer' => 'El código de aplicación debe ser un número entero.',
        ]);
        
        $codigo = $request->query('co_aplicacion');
        
        $co_usuario_log = Auth()->id();
        $affected = DB::update("UPDATE c001t_aplicaciones as ap2 SET 
                co_estatus_aplicacion = edos.co_estatus_siguiente,
                co_usuario_log = ? 
                FROM i001t_estatus_aplicaciones as edos 
                WHERE edos.co_estatus_aplicacion = ap2.co_estatus_aplicacion
                and ap2.co_aplicacion = ?", 
            [$co_usuario_log, $codigo]);
        sleep(2);
        if($affected > 0){            
          
           return redirect()->route('dashboard.team-details', ['co_aplicacion' => $codigo])
            ->with('success_register', 'Aplicación #' . $codigo . ' actualizada correctamente');
        }
        return redirect()->back()
        ->with('error_f','Aplicacion #'. $request->co_aplicacion.' no pudo ser actualizada');
    }

    public function stopApp(Request $request){
        $request->validate([
            'co_aplicacion' => 'required|integer',
        ], [
            'co_aplicacion.required' => 'El código de aplicación es obligatorio.',
            'co_aplicacion.integer' => 'El código de aplicación debe ser un número entero.',
        ]);
        
        $codigo = $request->input('co_aplicacion');
        
        $co_usuario_log = Auth()->id();
        $affected = DB::update("UPDATE c001t_aplicaciones as ap2 SET 
                co_estatus_aplicacion = 378,
                co_usuario_log = ?                 
                WHERE ap2.co_aplicacion = ?", 
            [$co_usuario_log, $codigo]);
        if($affected > 0){
            return redirect()->route('dashboard.team-details', ['co_aplicacion' => $codigo]);
        }
        return redirect()->back()
        ->with('error_f','Aplicacion #'. $request->co_aplicacion.' no pudo ser actualizada');
    }

    public function cancelApp(Request $request){
        $request->validate([
            'co_aplicacion' => 'required|integer',
        ], [
            'co_aplicacion.required' => 'El código de aplicación es obligatorio.',
            'co_aplicacion.integer' => 'El código de aplicación debe ser un número entero.',
        ]);
        
        $codigo = $request->input('co_aplicacion');
        
        $co_usuario_log = Auth()->id();
        $affected = DB::update("UPDATE c001t_aplicaciones as ap2 SET 
                co_estatus_aplicacion = 372,
                co_usuario_log = ? 
                WHERE ap2.co_aplicacion = ?", 
            [$co_usuario_log, $codigo]);
        if($affected > 0){
           return redirect()->route('dashboard.team-details', ['co_aplicacion' => $codigo]);
        }
        return redirect()->back()
        ->with('error_f','Aplicacion #'. $request->co_aplicacion.' no pudo ser actualizada');
    }

    public function activateApp(Request $request){
        $request->validate([
            'co_aplicacion' => 'required|integer',
        ], [
            'co_aplicacion.required' => 'El código de aplicación es obligatorio.',
            'co_aplicacion.integer' => 'El código de aplicación debe ser un número entero.',
        ]);
        
        $codigo = $request->input('co_aplicacion');
        
        $co_usuario_log = Auth()->id();
        $affected = DB::update("UPDATE c001t_aplicaciones as ap2 SET 
                co_estatus_aplicacion = 370,
                co_usuario_log = ?                 
                WHERE ap2.co_aplicacion = ?", 
            [$co_usuario_log, $codigo]);
        if($affected > 0){
            return redirect()->route('dashboard.team-details', ['co_aplicacion' => $codigo]);
        }
        return redirect()->back()
        ->with('error_f','Aplicacion #'. $request->co_aplicacion.' no pudo ser actualizada');
    }

    public function workOrder(Request $request){
        try {
            // Validar que existe el código de aplicación
            
            $request->validate([
                'co_aplicacion' => 'required|integer',
            ], [
                'co_aplicacion.required' => 'El código de aplicación es obligatorio.',
                'co_aplicacion.integer' => 'El código de aplicación debe ser un número entero.',
            ]);
            
            //$request->co_cliente, $request->co_metodo_pago_proyecto
            $codigoAplicacion = $request->co_aplicacion;
            $app = DB::table('c001t_aplicaciones')
            ->where('co_aplicacion', $codigoAplicacion)
            ->first();            
            $co_cliente = $app->co_cliente;
            $co_metodo_pago_proyecto = $app->co_metodo_pago_proyecto ?? 2;            
            $storage = Storage::disk('public');
            $exist_orden = false;
            if (!empty($app->tx_url_orden_trabajo)) {
                if ($storage->exists($app->tx_url_orden_trabajo)) {
                    $storage->delete($app->tx_url_orden_trabajo);
                    Log::info('1. Regenerando el PDF');
                    GenerarReporte::dispatch($co_cliente, $co_metodo_pago_proyecto,$app->tx_url_orden_trabajo)
                    ->delay(now()->addSeconds(6));     
                    $exist_orden = true;
                    Log::info('2. Se regenero el PDF');
                    Log::info("Archivo eliminado exitosamente: {$app->tx_url_orden_trabajo}");
                } else {
                    Log::info("El archivo no existe: {$app->tx_url_orden_trabajo}");
                }
            } else {
                Log::info("El archivo no existe: {$app->tx_url_orden_trabajo}");
            }            
                       
            
            if(!$exist_orden){
              session()->flash('error_f','La Orden de Trabajo no existe');
              return redirect()->route('dashboard.team-details', ['co_aplicacion' => $app->co_aplicacion]);
              
            }
            session()->flash('success_register','Orden de Trabajo Actualizada');
            return redirect()->route('dashboard.team-details', ['co_aplicacion' => $app->co_aplicacion]);           
            
            
    
        } catch (\Exception $e) {            
            Log::info("Warning Creando Precalificacion: ".$e->getMessage());
            session()->flash('error_f','Aplicacion #'. $request->co_aplicacion.' orden no se actualizo');
            return redirect()->back()
            ->with('error_f','Aplicacion #'. $request->co_aplicacion.' orden no se actualizo');
        }
       
    }    

    public function updateFinancialStatus(UpdateFinancialStatusRequest $request)
    {        
        $validatedData = $request->validated();
        $co_aplicacion = $validatedData['co_aplicacion'];
        $status_financial = $validatedData['status_financial'];
        $co_usuario_log = Auth()->id();
        $affected = DB::update("UPDATE c001t_aplicaciones as ap2 SET 
            co_estatus_financiero = ?,
            co_usuario_log = ?
            WHERE ap2.co_aplicacion = ?", 
            [$status_financial, $co_usuario_log, $co_aplicacion]);
        
        if($affected > 0){
            Session::flash('success_register','Actualización exitosa de Estatus Financiero');
            return redirect()->route('dashboard.team-details', ['co_aplicacion' => $co_aplicacion]);
            //->with('success_register','Actualización exitosa de Estatus Financiero');
        }
    
        return redirect()->back()
           ->with('error_f','Aplicación #'. $co_aplicacion.' Estatus financiero no pudo ser actualizado');             
    }

    public function getStatusFinanciero($financed){
        $estadosUsados = [0,375, 376, 377, 381];
        $data = [];
        $financed = trim($financed);
        if($financed === 'Financed'){
            $data = I002tEstatusFinanciero::whereIn('co_estatus_financiero',$estadosUsados)
            ->orderBy('in_orden', 'ASC')
            ->get();    
        }else{
            $data = I002tEstatusFinanciero::where('bo_tipo_estatus',false)
            ->orderBy('co_estatus_financiero', 'ASC')
            ->get();
        }        
        return $data;
    }   

    private function getApplicationLogs($codigo)
    {
    
        
        $applicationId = is_object($codigo) ? $codigo->co_aplicacion : $codigo;
        
        $sql = "SELECT 
		log1.codigo,
		log1.tx_accion || ' por ' || usr1.tx_primer_nombre || ' ' || usr1.tx_primer_apellido as tx_accion,  
		log1.fe_registro, 
		log1.tipo_log,
		ap1.co_cliente,
		ap1.co_cliente2
        FROM log_sistema as log1 
        INNER JOIN public.c001t_aplicaciones as ap1 ON (ap1.co_aplicacion = $applicationId
        )
        LEFT JOIN public.c002t_usuarios as usr1 ON (usr1.co_usuario = log1.co_usuario) 
        WHERE 
		(log1.codigo IN (ap1.co_aplicacion, ap1.co_cliente, ap1.co_cliente2)) and tipo_log = 3
        ORDER BY log1.co_log DESC";

         $datos = DB::select($sql);
         return collect($datos)->map(function($item) {
            return (object) $item;
        });
    } 
    
    public function updateFinancialInfo(FinancieraRequest $request)
    {
        $validatedData = $request->validated(); 
        $sql = "UPDATE c001t_aplicaciones  SET                     
            co_financiera = ?, 
            co_tipo_financiamiento = ?, 
            tx_rango = ?,            
            tx_meses  =?,
            nu_porcentajeap = ?,
            nu_tasa_interes = ?,
            nu_pago_mensual =  ?
            WHERE co_aplicacion = ?
            ";
        $valores = [
            $validatedData['co_financiera'],
            $validatedData['co_tipo_financiamiento'],
            $validatedData['tx_rango'],            
            $validatedData['tx_meses'],
            $validatedData['nu_porcentajeap'],
            $validatedData['nu_tasa_interes'],
            $validatedData['nu_pago_mensual'],
            $validatedData['co_aplicacion']
        ];
        $affected = DB::update($sql, $valores);

        if($affected > 0)
        {
            return redirect()->route('dashboard.team-details', ['co_aplicacion' => $validatedData['co_aplicacion']])
            ->with('success_register', 'Aplicación #' . $validatedData['co_aplicacion'] . ' Actualizada la Información Financiera Correctamente')
            ->with('tabActive', 'info-financiera-tab');
        }
        return redirect()->back()
        ->with('error_f','Aplicación #'. $validatedData['co_aplicacion'].' No Pudo Ser Actualizada la Información Financiera');
        
    }

    protected function updateNotificationApp($co_usuario, $co_aplicacion) {
        // Check if there are any unread notifications for this user and application
        $hasUnread = DB::table('c036t_usuarios_notificacion_his')
            ->where('co_usuario', $co_usuario)
            ->where('co_aplicacion', $co_aplicacion)
            ->where('bo_visto', false)
            ->whereIn('co_tiponoti', [1, 6, 7]) 
            ->exists(); // Use exists() for efficiency

        // If there are no unread notifications, no update is needed.
        if (!$hasUnread) {
            Log::info('No unread notifications found for user ' . $co_usuario . ' and application ' . $co_aplicacion . '. Skipping update.');
            return true; // Indicate success as no update was necessary.
        }

        // If there are unread notifications, proceed with the transaction
        try {
            $resultado = DB::transaction(function () use ($co_usuario, $co_aplicacion) {
                // Primera consulta: Actualizar c035t_usuarios_notificacion
                $sql1 = "
                    WITH notificaciones_no_vistas AS (
                        SELECT
                            co_usuario,
                            co_tiponoti,
                            COUNT(*) as cantidad
                        FROM c036t_usuarios_notificacion_his
                        WHERE co_usuario = :co_usuario
                        AND co_aplicacion = :co_aplicacion
                        AND bo_visto = False
                        AND co_tiponoti IN (1, 6, 7)
                        GROUP BY co_usuario, co_tiponoti
                    )
                    UPDATE c035t_usuarios_notificacion
                    SET
                        nu_notifica = CASE
                            WHEN nu_notifica - n.cantidad <= 0 THEN 0
                            ELSE nu_notifica - n.cantidad
                        END,
                        bo_visto = CASE
                            WHEN nu_notifica - n.cantidad <= 0 THEN TRUE
                            ELSE FALSE
                        END
                    FROM notificaciones_no_vistas n
                    WHERE c035t_usuarios_notificacion.co_usuario = n.co_usuario
                    AND c035t_usuarios_notificacion.co_tiponoti = n.co_tiponoti";

                $filasActualizadas1 = DB::update($sql1, [
                    'co_usuario' => $co_usuario,
                    'co_aplicacion' => $co_aplicacion
                ]);

                // Segunda consulta: Actualizar c036t_usuarios_notificacion_his
                $sql2 = "
                    UPDATE c036t_usuarios_notificacion_his
                    SET bo_visto = TRUE
                    WHERE co_usuario = :co_usuario
                    AND co_aplicacion = :co_aplicacion
                    AND bo_visto = FALSE
                    AND co_tiponoti IN (1, 6, 7)";

                $filasActualizadas2 = DB::update($sql2, [
                    'co_usuario' => $co_usuario,
                    'co_aplicacion' => $co_aplicacion
                ]);

                return $filasActualizadas1 + $filasActualizadas2;
            });
            
            if ($resultado > 0) {                
                return true;
            } else {                
                return true;
            }


        } catch (\Exception $e) {            
            return false;
        }
    }
    

}