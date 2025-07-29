<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\C002tUsuario;
use App\Models\C001tAplicacione;
use App\Models\I008tOficina;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use stdClass;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\SalesExport;
use App\Exports\TeamSalesExport;
use App\Exports\ExportTeam;
use Illuminate\Support\Number;
use App\Services\Reports\TeamLevel;
use Illuminate\Support\Arr;
use App\Models\DateHelper;
use Illuminate\Support\Facades\Session;

class ReportController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    protected $codigoUsuarioLogueado;
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('IsActive');
    }

    public function index(Request $request)
    {
        
        $co_usuario_logueado = Auth()->id(); //19, 1
        $this->codigoUsuarioLogueado = $co_usuario_logueado;
        
        $roles_usuarios = $this->getRolUsuario($this->codigoUsuarioLogueado);
        $bandMisVentas = 0;
        $bandMisVentasEquipo = 0;
        
        $rol = "";
        $textInicoTorta = ' últimos 3 meses';
        if ($roles_usuarios < 0)
        {
            
            return view('dashboard.report.report')
            ->with('meses', [])
            ->with('data', [])
            ->with('leyenda', "No hay información")
            ->with('data_torta', [])
            ->with('leyenda_torta', "No hay información")
            ->with('sub_leyenda_torta', "No hay información")
            ->with('message_error', "Al parecer hay un problema con los roles");
        } 
        
        $bandMisVentas = ($roles_usuarios >= 0  && $roles_usuarios < 5 || $roles_usuarios == 11) ? 1 : 0;
        $bandMisVentasEquipo = ($roles_usuarios >=5 && $roles_usuarios <= 10 ) ? 1 : 0;
        
        $misventas = $this->mySales($request);
        $ventasTeam = $this->myTeamSales($request);
        if ($bandMisVentas == 1) {
            //ESTE CASO CORRESPONDE ROLES BAJOS DE 0-4 Y 11 
            $eventos = [];
            $graficoBarra = $this->graficoBarraBajoRol($request);  
            $graficoTorta = $this->graficoTortaBajoRol($request);            
            return view('dashboard.report.report')
            ->with('meses', $graficoBarra['meses'])
            ->with('data', $graficoBarra['data'])
            ->with('leyenda', $graficoBarra['leyenda'])
            ->with('data_torta',$graficoTorta['data_torta'])  //
            ->with('leyenda_torta', $graficoTorta['leyenda_torta'].$textInicoTorta) //
            ->with('sub_leyenda_torta', $graficoTorta['sub_leyenda_torta']) //
            ->with('eventos', $eventos)
            ->with('rol', $rol)
            ->with('altoRol', false)
            ->with('misVentas', $misventas)
            ->with('ventasTeam',$ventasTeam);

        } elseif ($bandMisVentasEquipo == 1) {
            //GRAFICAS PARA CUANDO SON ROLES SUPERIORES A 5
           
                $data_torta = [];
                $graficoBarra = $this->graficoBarraAltoRol($request);
                $graficoTorta = $this->graficoTortaAltoRol($request);
                $eventos = [];
                return view('dashboard.report.report')
                ->with('meses', $graficoBarra['meses'])
                ->with('data', $graficoBarra['data'])
                ->with('leyenda', $graficoBarra['leyenda'])
                ->with('data_torta', $graficoTorta['data_torta'])
                ->with('leyenda_torta', $graficoTorta['leyenda_torta'].$textInicoTorta)
                ->with('sub_leyenda_torta', $graficoTorta['sub_leyenda_torta'])
                ->with('eventos', $eventos)
                ->with('rol', $rol)
                ->with('altoRol', true)
                ->with('misVentas', $misventas)
                ->with('ventasTeam',$ventasTeam);
        } 
        
    }

    public function bar(Request $request)
    {
        
        $co_usuario_logueado = Auth()->id(); 
        $this->codigoUsuarioLogueado = $co_usuario_logueado;
        $roles_usuarios = $this->getRolUsuario($this->codigoUsuarioLogueado);
        $bandMisVentas = 0;
        $bandMisVentasEquipo = 0;
        $rol = "";
        
        // Log para depuración
        \Log::info('Petición recibida en report.bar', [
            'type' => $request->type,
            'startDate' => $request->startDate,
            'endDate' => $request->endDate,
            'method' => $request->method()
        ]);
        
        if ($roles_usuarios < 0)
        {
            return response()->json([
                'meses' => [],
                'data' => [],
                'leyenda' => 'No hay información',
                'data_torta' => [],
                'leyenda_torta' => 'No hay información',
                'sub_leyenda_torta'=> 'No hay información',
                'message_error', 'Al parecer hay un problema con los roles',
            ], 204);
        }        
        
        $bandMisVentas = ($roles_usuarios >= 0  && $roles_usuarios < 5 || $roles_usuarios == 11) ? 1 : 0;
        $bandMisVentasEquipo = ($roles_usuarios >=5 && $roles_usuarios <= 10) ? 1 : 0;
        
        if ($bandMisVentas == 1) {
        
            $graficoBarra = $this->graficoBarraBajoRol($request);  
            return response()->json([
                'meses' => $graficoBarra['meses'],
                'data' => $graficoBarra['data'],
                'leyenda' => $graficoBarra['leyenda'],
                'rangoConsulta' => $graficoBarra['rangoConsulta'],
            ], 200);

        } elseif ($bandMisVentasEquipo == 1){
       
                $graficoBarra = $this->graficoBarraAltoRol($request);
                return response()->json([
                    'meses' => $graficoBarra['meses'],
                    'data' => $graficoBarra['data'],
                    'leyenda' => $graficoBarra['leyenda'],
                    'rangoConsulta' => $graficoBarra['rangoConsulta'],
                ], 200);
        }    
        
        
    }

    public function pie(Request $request)
    {
        
        $co_usuario_logueado = Auth()->id(); 
        $this->codigoUsuarioLogueado = $co_usuario_logueado;
        $roles_usuarios = $this->getRolUsuario($this->codigoUsuarioLogueado);
        $bandMisVentas = 0;
        $bandMisVentasEquipo = 0;
        $rol = "";
        if ($roles_usuarios < 0)
        {
            return response()->json([
                'meses' => [],
                'data' => [],
                'leyenda' => 'No hay información',
                'data_torta' => [],
                'leyenda_torta' => 'No hay información',
                'sub_leyenda_torta'=> 'No hay información',
                'message_error', 'Al parecer hay un problema con los roles',
            ], 204);
        }        
        
        $bandMisVentas = ($roles_usuarios >= 0  && $roles_usuarios < 5 || $roles_usuarios == 11) ? 1 : 0;
        $bandMisVentasEquipo = ($roles_usuarios >=5 && $roles_usuarios <= 10) ? 1 : 0;
        
        if ($bandMisVentas == 1) {
          
            $graficoPie = $this->graficoTortaBajoRol($request);  
            return response()->json([
                'data_torta' => $graficoPie['data_torta'],
                'leyenda_torta' => $graficoPie['leyenda_torta'],
                'sub_leyenda_torta' => $graficoPie['sub_leyenda_torta'],
                'rangoConsulta' => $graficoPie['rangoConsulta'],
            ], 200);

        } elseif ($bandMisVentasEquipo == 1){
                
                $graficoPie = $this->graficoTortaAltoRol($request);
                return response()->json([
                    'data_torta' => $graficoPie['data_torta'],
                    'leyenda_torta' => $graficoPie['leyenda_torta'],
                    'sub_leyenda_torta' => $graficoPie['sub_leyenda_torta'],
                    'rangoConsulta' => $graficoPie['rangoConsulta'],
                ], 200);
        }        
    }

    public function getRolUsuario($codigoUsuarioLogueado)
    {
        $rol = "";
        $codigoRol = 0;
        $roles_usuarios = C002tUsuario::find($codigoUsuarioLogueado)->c014t_usuarios_roles()->get();
        
        if(count($roles_usuarios) > 0){
            foreach ($roles_usuarios as $key => $value) {
                $codigoRol = $value->co_rol;
                    switch ($value->co_rol) {
                        case 0:
                            $rol = "Sin Rol definido";
                            break;
                        case 1:
                            $rol = "Analista";
                            break;
                        case 2:
                            $rol = "Analista Sr.";
                            break;
                        case 3:
                            $rol = "Mentor";
                            break;
                        case 4:
                            $rol = "Mentor Sr.";
                            break;
                        case 5:
                            $rol = "Jr. Manager";
                            break;
                        case 6:
                            $rol = "Sr. Manager";
                            break;
                        case 7:
                            $rol = "Director";
                            break;
                        case 8:
                            $rol = "Dir. Regional";
                            break;
                        case 9:
                            $rol = "Master";
                            break;
                        case 10:
                            $rol = "Embajador";
                            break;          
                        case 11:
                            $rol = "Estudiante";
                            break;
                        default:
                            $rol = "...";
                  
                    }
                    
                }
            } 
            session(['rol_userlogin' => $rol]);          
            return   $codigoRol;
                 
    }

    public function graficoBarraAltoRol(Request $request)
    {
        
        $rangoConsulta = $this->rangoGraficoBarras($request);
        $co_usuario_logueado = $this->codigoUsuarioLogueado;
        $type = $request->type ? $request->type : "2"; // Default es monto (2), cantidad es (1)
        
        $sql = "SELECT 
                EXTRACT ( YEAR FROM fe_instalacion ) AS elanno,
                EXTRACT ( MONTH FROM fe_instalacion ) AS elmes,
                CASE
                        EXTRACT ( MONTH FROM fe_instalacion ) 
                        WHEN 1 THEN
                        'ENE' 
                        WHEN 2 THEN
                        'FEB' 
                        WHEN 3 THEN
                        'MAR' 
                        WHEN 4 THEN
                        'ABR' 
                        WHEN 5 THEN
                        'MAY' 
                        WHEN 6 THEN
                        'JUN' 
                        WHEN 7 THEN
                        'JUL' 
                        WHEN 8 THEN
                        'AGO' 
                        WHEN 9 THEN
                        'SEP' 
                        WHEN 10 THEN
                        'OCT' 
                        WHEN 11 THEN
                        'NOV' 
                        WHEN 12 THEN
                        'DIC' 
                    END AS MESES,
                    SUM ( nu_precio_total ) AS totales,
                    COUNT (*) AS cantidad
                FROM
                    c001t_aplicaciones 
                WHERE
                    $rangoConsulta
                    AND (
                        co_usuario IN (
                            WITH RECURSIVE JerarquiaConNiveles AS (
                            SELECT
                                co_usuario 
                            FROM
                                c002t_usuarios 
                            WHERE
                                co_usuario_padre = $co_usuario_logueado UNION ALL
                            SELECT
                                o.co_usuario 
                            FROM
                                c002t_usuarios o
                                JOIN JerarquiaConNiveles cte ON o.co_usuario_padre = cte.co_usuario 
                            ) SELECT
                            * 
                        FROM
                            JerarquiaConNiveles 
                        ) 
                        OR co_usuario = $co_usuario_logueado 
                    ) 
                GROUP BY
                    elanno,
                    elmes,
                    meses 
                ORDER BY
                    elanno,
                    elmes ASC;";

            $ventas_mi_equipo = DB::select($sql);

            $ele = count($ventas_mi_equipo);

            $meses = [];
            $data = [];
            $leyenda = "Sin información para mostrar";                    
            if ($ele > 0) {                   

                foreach ($ventas_mi_equipo as $key => $value) {
                    $meses[$key] = $value->meses;
                    $data[$key] = $type == "1" ? $value->cantidad : $value->totales;
                }
                $leyenda = $type == "1" ? 'Cantidad de ventas de mi equipo' : 'Ventas de mi equipo';
            } 

            
            return array(
                'misVentas' => $ventas_mi_equipo,
                'data' => $data,
                'meses' => $meses,
                'leyenda' => $leyenda,
                'rangoConsulta' => $rangoConsulta
            );

    }

    public function graficoBarraBajoRol(Request $request)
    {
       
        $rangoConsulta = $this->rangoGraficoBarras($request);
        $co_usuario_logueado = $this->codigoUsuarioLogueado;
        $type = $request->type ? $request->type : "2"; // Default es monto (2), cantidad es (1)
        $rangoConsulta = $rangoConsulta." AND co_usuario = '$co_usuario_logueado'"; 
        $sql = "SELECT 
            EXTRACT ( YEAR FROM fe_instalacion ) AS elanno,
            EXTRACT ( MONTH FROM fe_instalacion ) AS elmes,
            CASE
                    EXTRACT ( MONTH FROM fe_instalacion ) 
                    WHEN 1 THEN
                    'ENE' 
                    WHEN 2 THEN
                    'FEB' 
                    WHEN 3 THEN
                    'MAR' 
                    WHEN 4 THEN
                    'ABR' 
                    WHEN 5 THEN
                    'MAY' 
                    WHEN 6 THEN
                    'JUN' 
                    WHEN 7 THEN
                    'JUL' 
                    WHEN 8 THEN
                    'AGO' 
                    WHEN 9 THEN
                    'SEP' 
                    WHEN 10 THEN
                    'OCT' 
                    WHEN 11 THEN
                    'NOV' 
                    WHEN 12 THEN
                    'DIC' 
                END AS MESES,
                SUM ( nu_precio_total ) AS totales,
                COUNT (*) AS cantidad
            FROM
                c001t_aplicaciones 
            WHERE
                $rangoConsulta
            GROUP BY
                elanno,
                elmes,
                meses 
            ORDER BY
                elanno,
                elmes ASC";
            
            $mis_ventas = DB::select($sql);
            $meses = [];
            $data = [];
            $leyenda = "Sin información para mostrar";
            if(count($mis_ventas)>0){
                foreach ($mis_ventas as $key => $value) {
                    $meses[$key] = $value->meses;
                    $data[$key] = $type == "1" ? $value->cantidad : $value->totales;
                }
                $leyenda = $type == "1" ? 'Cantidad de mis ventas' : 'Mis ventas';
            }     
            return array(
                'misVentas' => $mis_ventas,
                'data' => $data,
                'meses' => $meses,
                'leyenda' => $leyenda,
                'rangoConsulta' => $rangoConsulta
            );  
    }

    public function graficoTortaAltoRol(Request $request)
    {
        $co_usuario_logueado = $this->codigoUsuarioLogueado;
        $data_torta = [];
        $rangoConsulta = $this->rangoGraficoTorta($request);
        
        $co_usuario_padre = C002tUsuario::find($co_usuario_logueado)->co_usuario_padre;
        
        $sql_p = "WITH ventas AS (
                   SELECT
                        A1.co_usuario,
                       B1.tx_primer_nombre || ' ' || B1.tx_primer_apellido as usuario,
                        SUM ( nu_precio_total ) AS totales 
                    FROM
                        c001t_aplicaciones AS A1
                        INNER JOIN c002t_usuarios AS B1 ON ( A1.co_usuario = B1.co_usuario ) 
                    WHERE
                       $rangoConsulta
                        AND (A1.co_usuario = $co_usuario_logueado or A1.co_usuario IN (
                            WITH RECURSIVE JerarquiaConNiveles AS (
                            SELECT
                                co_usuario 
                            FROM
                                c002t_usuarios 
                            WHERE
                                co_usuario_padre = $co_usuario_logueado UNION ALL
                            SELECT
                                o.co_usuario 
                            FROM
                                c002t_usuarios o
                                JOIN JerarquiaConNiveles cte ON o.co_usuario_padre = cte.co_usuario 
                            ) SELECT
                            * 
                        FROM
                            JerarquiaConNiveles 
                        ))
                    GROUP BY
                        A1.co_usuario,
                        usuario 
                    ),
                    total_ventas AS ( SELECT SUM ( totales ) AS total FROM ventas ) SELECT
                    co_usuario,
                    usuario,
                    totales,
                    ( totales / total ) * 100 AS porcentaje_del_total,
                    total AS total_venta_equipo 
                    FROM
                    ventas,
                    total_ventas 
                    ORDER BY
                    porcentaje_del_total DESC 
                    LIMIT 10";
        
        $leyenda_torta = "Ventas top 10 de mi equipo";
        $sub_leyenda_torta ='';
        $top_ventas_equipo = DB::select($sql_p);
        $ele = count($top_ventas_equipo);
        if ($ele > 0) {

            $v_total = 0;
            $v_mejores = 0;
            $v_resto = 0;

            foreach ($top_ventas_equipo as $key => $value) {
                $objAux = new stdClass();
                $objAux->name = $value->usuario;
                $objAux->value = $value->totales;
                $data_torta[$key] = $objAux;

                $v_total = $value->total_venta_equipo;
                $v_mejores += $value->totales;
            }                    
        } else {

            $sub_leyenda_torta = "Sin información para el mes en curso";
            $objAux = new stdClass();
            $objAux->name = "Sin ventas";
            $objAux->value = 0;
            array_push($data_torta, $objAux);
        }

        return array(
            'leyenda_torta' => $leyenda_torta,
            'sub_leyenda_torta' => $sub_leyenda_torta,
            'data_torta' => $data_torta,
            'rangoConsulta' => $rangoConsulta
        );

    }

    public function graficoTortaBajoRol(Request $request)
    {
        
        $leyenda_torta = 'Sin informacion para mostrar';
        $sub_leyenda_torta ='';
        $data_torta = [];
         $rangoConsulta = $this->rangoGraficoTorta($request);
        $co_usuario_logueado = $this->codigoUsuarioLogueado;
        $oficina =  $this->getOficina($co_usuario_logueado);   
        if(count($oficina) > 0)
        {
       
            //$co_usuario_padre = C002tUsuario::find($co_usuario_logueado)->co_usuario_padre;
            $sql_p ="WITH ventas AS (
			SELECT
					A1.co_usuario,
					B1.tx_primer_nombre || ' ' || B1.tx_primer_apellido as usuario,
					D1.tx_nombre AS OFICINA,
					SUM ( nu_precio_total ) AS totales 
			FROM
					c001t_aplicaciones AS A1
					INNER JOIN c002t_usuarios AS B1 ON ( A1.co_usuario = B1.co_usuario ) 
					INNER JOIN c012t_usuarios_oficinas AS C1 ON ( B1.co_usuario = C1.co_usuario )
					INNER JOIN i008t_oficinas AS D1 ON ( C1.co_oficina = D1.co_oficina ) 
			WHERE
				$rangoConsulta 
					AND A1.co_usuario IN (
								SELECT co_usuario FROM c012t_usuarios_oficinas
								where co_oficina IN (select co_oficina  FROM c012t_usuarios_oficinas 
								where co_usuario = $co_usuario_logueado)
								) 
			GROUP BY
				A1.co_usuario,
				usuario, OFICINA
			),
			total_ventas AS ( SELECT SUM ( totales ) AS total FROM ventas ) SELECT
			co_usuario,
			usuario, OFICINA,
			totales,
			( totales / total ) * 100 AS porcentaje_del_total,
			total AS total_venta_equipo 
			FROM
			ventas,
			total_ventas 
			ORDER BY
			porcentaje_del_total DESC 
			LIMIT 10";            

            $leyenda_torta = "Top 10 de mi oficina ". $oficina[0]->office_city;
            $top_ventas_equipo = DB::select($sql_p);
            $ele = count($top_ventas_equipo);
            
            if ($ele > 0) {

                $v_total = 0;
                $v_mejores = 0;
                $v_resto = 0;

                foreach ($top_ventas_equipo as $key => $value) {
                    $objAux = new stdClass();
                    $objAux->name = $value->usuario;
                    $objAux->value = $value->totales;
                    $data_torta[$key] = $objAux;

                    $v_total = $value->total_venta_equipo;
                    $v_mejores += $value->totales;
                }
     
            } else {

                $sub_leyenda_torta = "Sin información para el mes en curso";
                $objAux = new stdClass();
                $objAux->name = "Sin ventas";
                $objAux->value = 0;
                array_push($data_torta, $objAux);
            }    
        }
        
        return array(
            'leyenda_torta' => $leyenda_torta,
            'sub_leyenda_torta' => $sub_leyenda_torta,
            'data_torta' => $data_torta,
            'rangoConsulta' => $rangoConsulta
        );

    }
    
    public function getOficina($usuarioLogueado)
    {
        $sql = 'select "Office_City" as office_city from c002t_usuarios where co_usuario = '.$usuarioLogueado;
        $resp_oficina = DB::select($sql);
        return   $resp_oficina;      
    }

    private function rangoGraficoBarras(Request $request)
    {
        $startDate = $request->startDate ?? "";
        $endDate = $request->endDate ?? "";
        
        $rangoConsulta = "( fe_instalacion BETWEEN date_trunc( 'month', CURRENT_DATE - INTERVAL '12 months' ) AND now()) AND co_estatus_aplicacion = 369 "; 

        if(!empty($startDate) && !empty($endDate)){
            $timestamp = strtotime($startDate);
            $startDate = date("Y-m-d", $timestamp);
            $timestamp1 = strtotime($endDate);
            $endDate = date("Y-m-d", $timestamp1);
            $rangoConsulta = "(fe_instalacion BETWEEN DATE('$startDate') AND DATE('$endDate')) AND co_estatus_aplicacion = 369 "; 
        }    
        if(!empty($startDate) && empty($endDate)){
            $timestamp = strtotime($startDate);
            $startDate = date("Y-m-d", $timestamp);
            $endDate = Carbon::parse($startDate)->addMonths(12);
            $endDate = $endDate->format('Y-m-d');
            $rangoConsulta = "(fe_instalacion BETWEEN DATE('$startDate') AND DATE('$endDate')) AND co_estatus_aplicacion = 369 "; 
        }            
        if(empty($startDate) && !empty($endDate)){
            $timestamp = strtotime($endDate);
            $endDate = date("Y-m-d", $timestamp);
            $startDate = Carbon::parse($endDate)->subMonths(12);
            $startDate = $startDate->format('Y-m-d');
            $rangoConsulta = "(fe_instalacion BETWEEN DATE('$startDate') AND DATE('$endDate')) AND co_estatus_aplicacion = 369 "; 
        }
        return $rangoConsulta;
    }

    private function rangoGraficoTorta(Request $request)
    {
        $startDate = $request->startDate ?? "";
        $endDate = $request->endDate ?? "";
        
        $rangoConsulta = "(fe_instalacion BETWEEN date_trunc('month', CURRENT_DATE - INTERVAL '3 months') and now()) AND co_estatus_aplicacion = '369'"; 
        
        if(!empty($startDate) && !empty($endDate)){
            $timestamp = strtotime($startDate);
            $startDate = date("Y-m-d", $timestamp);
            $timestamp1 = strtotime($endDate);
            $endDate = date("Y-m-d", $timestamp1);
           $rangoConsulta = "(fe_instalacion BETWEEN DATE('$startDate') and DATE('$endDate')) AND co_estatus_aplicacion = '369'";
        }    
        if(!empty($startDate) && empty($endDate)){
            $timestamp = strtotime($startDate);
            $startDate = date("Y-m-d", $timestamp);
            $endDate = Carbon::parse($startDate)->addMonths(12);
            $endDate = $endDate->format('Y-m-d');
            $rangoConsulta = "(fe_instalacion BETWEEN DATE('$startDate') and DATE('$endDate')) AND co_estatus_aplicacion = '369'";
        }            
        if(empty($startDate) && !empty($endDate)){
            $timestamp = strtotime($endDate);
            $endDate = date("Y-m-d", $timestamp);
            $startDate = Carbon::parse($endDate)->subMonths(12);
            $startDate = $startDate->format('Y-m-d');
            $rangoConsulta = "(fe_instalacion BETWEEN DATE('$startDate') and DATE('$endDate')) AND co_estatus_aplicacion = '369'";
        }
        
        return $rangoConsulta;
        
    }

    public function mySales(Request $request)
    {
        $co_usuario_logueado = Auth()->id();

        $condicion = "c003t_clientes.co_qb_owner = $co_usuario_logueado AND
        co_estatus_aplicacion <> '372' and 
        fe_instalacion BETWEEN '2024-01-01'::date and '2024-08-01'::date";

        if($request->input('date1')!="" && $request->input('date2')!=""){
            
            $fecha1 = $request->input('date1'); 
            $fecha2 = $request->input('date2');

            $fecha1 = Carbon::parse($fecha1)->isoFormat('Y-M-D');
            $fecha2 = Carbon::parse($fecha2)->isoFormat('Y-M-D');

           
            $condicion = "c003t_clientes.co_qb_owner = $co_usuario_logueado AND
            co_estatus_aplicacion <> '372' and 
            fe_instalacion BETWEEN DATE('$fecha1') and DATE('$fecha2')";
        }

        $order = "c001t_aplicaciones.fe_creacion DESC";
        

        if($request->select_order == 2){
            $order = "c003t_clientes.tx_ciudad ASC";
        }

        if($request->select_order == 3){
            $order = "c003t_clientes.tx_estado ASC";
        }

        $sql="SELECT 
            c001t_aplicaciones.co_aplicacion,
            c001t_aplicaciones.fe_creacion,
            c003t_clientes.tx_primer_nombre,
            c003t_clientes.tx_primer_apellido,
            c003t_clientes.tx_telefono,
            c003t_clientes.tx_ciudad,
            c003t_clientes.tx_estado,
            c003t_clientes.tx_direccion1,
            c003t_clientes.tx_direccion2,
            c003t_clientes.tx_zip,
            c003t_clientes.tx_email,
            c003t_clientes.co_cliente,
            (
                SELECT i001t_estatus_aplicaciones.tx_nombre 
                FROM i001t_estatus_aplicaciones
                JOIN c026t_aplicaciones_estatus_historial 
                    ON i001t_estatus_aplicaciones.co_estatus_aplicacion = c026t_aplicaciones_estatus_historial.co_estatus_aplicacion 
                WHERE c026t_aplicaciones_estatus_historial.co_aplicacion = c001t_aplicaciones.co_aplicacion 
                ORDER BY c026t_aplicaciones_estatus_historial.co_aplicacion_estatus_historial DESC 
                LIMIT 1
            ) AS estatus_mas_reciente,
            (
                SELECT c026t_aplicaciones_estatus_historial.fe_activacion_estatus
                FROM c026t_aplicaciones_estatus_historial
                WHERE c026t_aplicaciones_estatus_historial.co_aplicacion = c001t_aplicaciones.co_aplicacion 
                ORDER BY c026t_aplicaciones_estatus_historial.co_aplicacion_estatus_historial DESC 
                LIMIT 1
            ) AS fe_activacion_estatus_mas_reciente, 
            c003t_clientes.co_qb_setter,
            c003t_clientes.co_qb_owner,
            U1.tx_primer_nombre  || ' ' || U1.tx_primer_apellido as SetterName,
            U2.tx_primer_nombre  || ' ' || U2.tx_primer_apellido as OwnerName,
            c001t_aplicaciones.nu_precio_total,
            SUM(c001t_aplicaciones.nu_precio_total) OVER () AS total_periodo
        FROM 
            c001t_aplicaciones
        JOIN 
            c003t_clientes ON c001t_aplicaciones.co_cliente = c003t_clientes.co_cliente
        LEFT JOIN    
            c002t_usuarios U1 ON U1.co_quick_base_id = c003t_clientes.co_qb_setter
        LEFT JOIN    
            c002t_usuarios U2 ON U2.co_quick_base_id = c003t_clientes.co_qb_owner
        WHERE 
            $condicion
        ORDER BY $order";    
       
       $data = DB::select($sql);
        
       if(count($data) > 0){
        foreach($data as $item){
            $aux = explode(".",Number::currency((int)$item->nu_precio_total));
            $item->nu_precio_total = $aux[0];
            $aux = explode(".",Number::currency((int)$item->total_periodo));
            $item->total_periodo = $aux[0];
        }
        
       }
        return $data;                
    }

    public function sales(Request $request){
        
       $data = $this->mySales($request);
       
        if(count($data)>0){
           return Response::json(array('success'=>true,'msg'=>'Ventas ordenadas ', 'data' => $data), 200); 
        }else{
           return Response::json(array('error'=>false,'msg'=>'No se encontraron ventas'), 422);
        }

    }

    public function myTeamSales(Request $request){
        
        $co_usuario_logueado = Auth()->id();
        $condicion = "(fe_instalacion BETWEEN '2024-01-01'::date and '2024-08-01'::date) AND 
			co_estatus_aplicacion <> '372'";

        if($request->input('date1')!="" && $request->input('date2')!=""){
            
            $fecha1 = $request->input('date1'); 
            $fecha2 = $request->input('date2');

            $fecha1 = Carbon::parse($fecha1)->isoFormat('Y-M-D');
            $fecha2 = Carbon::parse($fecha2)->isoFormat('Y-M-D');

           $condicion = "(fe_instalacion BETWEEN DATE('$fecha1') and DATE('$fecha2')) AND 
           co_estatus_aplicacion <> '372'";
        }

        $order = "c001t_aplicaciones.fe_creacion DESC";

        if($request->select_order == 2){
            $order = "c003t_clientes.tx_ciudad ASC";
        }

        if($request->select_order == 3){
            $order = "c003t_clientes.tx_estado ASC";
        }

               
        $sql="WITH RECURSIVE JerarquiaConNiveles AS (
            SELECT co_usuario
            FROM c002t_usuarios
            WHERE co_usuario_padre = $co_usuario_logueado and co_estatus_usuario = 1
            UNION ALL
            SELECT o.co_usuario
            FROM c002t_usuarios o
            JOIN JerarquiaConNiveles cte ON o.co_usuario_padre = cte.co_usuario
        )
        SELECT 
            c001t_aplicaciones.co_aplicacion,
            c001t_aplicaciones.fe_creacion,
            c003t_clientes.tx_primer_nombre,
            c003t_clientes.tx_primer_apellido,
            c003t_clientes.tx_telefono,
            c003t_clientes.tx_ciudad,
            c003t_clientes.tx_estado,
            c003t_clientes.tx_direccion1,
            c003t_clientes.tx_direccion2,
            c003t_clientes.tx_zip,
            c003t_clientes.tx_email,
            c003t_clientes.co_cliente,
            (
                SELECT i001t_estatus_aplicaciones.tx_nombre 
                FROM i001t_estatus_aplicaciones
                JOIN c026t_aplicaciones_estatus_historial 
                    ON i001t_estatus_aplicaciones.co_estatus_aplicacion = c026t_aplicaciones_estatus_historial.co_estatus_aplicacion 
                WHERE c026t_aplicaciones_estatus_historial.co_aplicacion = c001t_aplicaciones.co_aplicacion 
                ORDER BY c026t_aplicaciones_estatus_historial.co_aplicacion_estatus_historial DESC 
                LIMIT 1
            ) AS estatus_mas_reciente,
            (
                SELECT c026t_aplicaciones_estatus_historial.fe_activacion_estatus
                FROM c026t_aplicaciones_estatus_historial
                WHERE c026t_aplicaciones_estatus_historial.co_aplicacion = c001t_aplicaciones.co_aplicacion 
                ORDER BY c026t_aplicaciones_estatus_historial.co_aplicacion_estatus_historial DESC 
                LIMIT 1
            ) AS fe_activacion_estatus_mas_reciente, 
            c003t_clientes.co_qb_setter,
            c003t_clientes.co_qb_owner,
            U1.tx_primer_nombre  || ' ' || U1.tx_primer_apellido as SetterName,
            U2.tx_primer_nombre  || ' ' || U2.tx_primer_apellido as OwnerName,
						c001t_aplicaciones.nu_precio_total,
						SUM(c001t_aplicaciones.nu_precio_total) OVER () AS total_periodo
        FROM 
            c001t_aplicaciones
        JOIN 
            c003t_clientes ON c001t_aplicaciones.co_cliente = c003t_clientes.co_cliente
        LEFT JOIN 
            c002t_usuarios U1 ON U1.co_quick_base_id = c003t_clientes.co_qb_setter
        LEFT JOIN 
            c002t_usuarios U2 ON U2.co_quick_base_id = c003t_clientes.co_qb_owner
        WHERE 
			$condicion and 
		    (c001t_aplicaciones.co_usuario = $co_usuario_logueado 
            OR c001t_aplicaciones.co_cliente IN (
                SELECT c.co_cliente
                FROM c002t_usuarios AS u
                INNER JOIN c003t_clientes AS c
                ON (c.co_quick_base_id = u.co_quick_base_id 
                    OR c.co_qb_setter = u.co_quick_base_id 
                    OR c.co_qb_owner = u.co_quick_base_id
                    OR c.co_usuario = u.co_usuario)
                WHERE u.co_usuario = $co_usuario_logueado 
                OR u.co_usuario_padre = $co_usuario_logueado
            ) 
            OR c001t_aplicaciones.co_usuario IN (
                SELECT co_usuario 
                FROM JerarquiaConNiveles
            ) 
            OR c001t_aplicaciones.co_usuario = $co_usuario_logueado)
        ORDER BY $order ";    
       
       $data = DB::select($sql);
       
       if(count($data)> 0){
        foreach($data as $item){
            $aux = explode(".",Number::currency((int)$item->nu_precio_total));
            $item->nu_precio_total = $aux[0];
            $aux = explode(".",Number::currency((int)$item->total_periodo));
            $item->total_periodo = $aux[0];
        }
       }
       
        return $data;

    }
    public function teamSales(Request $request){

       $data = $this->myTeamSales($request);
      
        if(count($data)>0){
           return Response::json(array('success'=>true,'msg'=>'Ventas ordenadas ', 'data' => $data), 200); 
        }else{
           return Response::json(array('error'=>false,'msg'=>'No se encontraron ventas'), 422);
        }
        
    }

    
    public function exportSales(Request $request){
        $sales = $this->mySales($request);
        $finalSales = array();
        if(count($sales)>0){
           foreach($sales as $item){
                $fecha_rf2 = "---"; 
                if($item->fe_activacion_estatus_mas_reciente){
                    $fecha_r2 = Carbon::parse($item->fe_activacion_estatus_mas_reciente);
                    $fecha_rf2 = $fecha_r2->isoFormat('MM/DD/YYYY');
                }   
                $status = $item->estatus_mas_reciente;
                if($item->estatus_mas_reciente==""){
                    $status = "---";
                }

                $fecha_r = Carbon::parse($item->fe_creacion);
                $fecha_rf = $fecha_r->isoFormat('MM/DD/YYYY'); 

                $mysales= array(
                    $item->co_aplicacion.' '.$item->tx_primer_nombre.' '.$item->tx_primer_apellido,
                    $item->tx_telefono,
                    $item->settername,
                    $item->ownername,
                    $item->tx_estado.' '.$item->tx_ciudad.' '.$item->tx_direccion1.','.$item->tx_direccion2,
                    $fecha_rf2,
                    $status,
                    $fecha_rf,
                    $item->nu_precio_total,    
                    $item->total_periodo
                );        
                
                array_push($finalSales,$mysales);
           }
        }
        $export = new SalesExport($finalSales);
        $date = Carbon::now();
        $file = 'sales-'.$date->toDateTimeString().'.xlsx';
        return Excel::download($export, $file);
    }

    public function exporTeamSales(Request $request){
        $sales = $this->myTeamSales($request);
        $finalSales = array();
        if(count($sales)>0){
           foreach($sales as $item){
                $fecha_rf2 = "---"; 
                if($item->fe_activacion_estatus_mas_reciente){
                    $fecha_r2 = Carbon::parse($item->fe_activacion_estatus_mas_reciente);
                    $fecha_rf2 = $fecha_r2->isoFormat('MM/DD/YYYY');
                }   
                $status = $item->estatus_mas_reciente;
                if($item->estatus_mas_reciente==""){
                    $status = "---";
                }

                $fecha_r = Carbon::parse($item->fe_creacion);
                $fecha_rf = $fecha_r->isoFormat('MM/DD/YYYY'); 

                $mysales= array(
                    $item->co_aplicacion.' '.$item->tx_primer_nombre.' '.$item->tx_primer_apellido,
                    $item->tx_telefono,
                    $item->settername,
                    $item->ownername,
                    $item->tx_estado.' '.$item->tx_ciudad.' '.$item->tx_direccion1.','.$item->tx_direccion2,
                    $fecha_rf2,
                    $status,
                    $fecha_rf,
                    $item->nu_precio_total,    
                    $item->total_periodo
                );        
                
                array_push($finalSales,$mysales);
           }
        }
        $export = new TeamSalesExport($finalSales);
        $date = Carbon::now();
        $file = 'teamsales-'.$date->toDateTimeString().'.xlsx';
        return Excel::download($export, $file);
    }

    public function teamLevel(Request $request){
        $team = new TeamLevel();
        //$data = $team->getDataTree();
        $data = $team->getData();        
        return view('dashboard.report.teamLevel',['team' => $data]);
    }

    /**
     * Retorna los datos del equipo en formato JSON para actualizaciones AJAX
     */
    public function getTeamLevelData(Request $request){
        $team = new TeamLevel();
        $data = $team->getData();        
        return response()->json($data);
    }

    public function exportTeam(Request $request){
        $team = new TeamLevel();
        $data = $team->getData(); 
        $finalTeam = array();
        if(count($data)> 0){
            foreach($data as $item){
                $row = array(
                     $item->ruta,
                     $item->rol,
                     $item->oficina
                );
                array_push($finalTeam,$row);                
            }
        }                  
        $export = new ExportTeam($finalTeam);
        
        $date = Carbon::now();
        $file = 'team-'.$date->toDateTimeString().'.xlsx';
        return Excel::download($export, $file);
    }

}
