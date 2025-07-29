<?php

namespace App\Services\Reports;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Illuminate\Support\Number;
use Illuminate\Support\Facades\DB;

class Application extends Model 
{
    use HasFactory;
  
    public function getRango(Request $request)
    {
        $startDate = $request->startDate ?? "";
        $endDate = $request->endDate ?? "";
        
        $rangoConsulta = "AND edo.fe_activacion_estatus BETWEEN (SELECT inicio FROM valores) AND (SELECT final FROM valores)";
        if(!empty($startDate) && !empty($endDate)){
            $timestamp = strtotime($startDate);
            $startDate = date("Y-m-d", $timestamp);
            $timestamp1 = strtotime($endDate);
            $endDate = date("Y-m-d", $timestamp1);                     
           $rangoConsulta = "AND edo.fe_activacion_estatus BETWEEN (SELECT inicio FROM valores) AND (SELECT final FROM valores)";
        }    
        if(!empty($startDate) && empty($endDate)){
            $timestamp = strtotime($startDate);
            $startDate = date("Y-m-d", $timestamp);
            $endDate = Carbon::parse($startDate)->addMonths(11);
            $endDate = $endDate->format('Y-m-d');                       
           $rangoConsulta = "AND edo.fe_activacion_estatus BETWEEN (SELECT inicio FROM valores) AND (SELECT final FROM valores)";
        }            
        if(empty($startDate) && !empty($endDate)){
            $timestamp = strtotime($endDate);
            $endDate = date("Y-m-d", $timestamp);
            $startDate = Carbon::parse($endDate)->subMonths(11);
            $startDate = $startDate->format('Y-m-d');                        
            $rangoConsulta = "AND edo.fe_activacion_estatus BETWEEN (SELECT inicio FROM valores) AND (SELECT final FROM valores)";
        }

        if(empty($startDate) && empty($endDate)){
            $today = Carbon::now();
            $endDate = $today->format('Y-m-d');
            $startDate = $today->subMonths(11)->format('Y-m-d');                        
            $rangoConsulta = "AND edo.fe_activacion_estatus BETWEEN (SELECT inicio FROM valores) AND (SELECT final FROM valores)";
        }

        return array(
            'rangoConsulta' => $rangoConsulta,
            'startDate' => $startDate,
            'endDate' => $endDate
        );   
    }
    public function getData(Request $request)
    {
        $rango = $this->getRango($request);
        $rangoConsulta = $rango['rangoConsulta'];
        $startDate = $rango['startDate'];
        $endDate = $rango['endDate'];
        
        $co_usuario_logueado = Auth()->id();
        $sql = "
            WITH RECURSIVE valores AS (
                SELECT DATE('$startDate') AS inicio, DATE('$endDate') AS final
            ), 
            losmeses AS (
                SELECT generate_series((SELECT inicio FROM valores), (SELECT final FROM valores), '1 month'::interval) AS mes
            ), 
            equipo AS (
                -- Obtener el equipo incluyendo el líder y sus dependientes
                SELECT co_usuario FROM c002t_usuarios WHERE co_usuario = $co_usuario_logueado
                UNION ALL
                SELECT c.co_usuario FROM c002t_usuarios c 
                INNER JOIN equipo e ON c.co_usuario_padre = e.co_usuario
            ),
            aplicaciones_distintas AS (
                -- Identificar aplicaciones únicas que hayan pasado por el estatus 366
                SELECT DISTINCT ON (co_aplicacion) co_aplicacion, fe_activacion_estatus
                FROM c026t_aplicaciones_estatus_historial
                WHERE co_estatus_aplicacion = 366
                ORDER BY co_aplicacion, fe_activacion_estatus
            ),
            ventastodas AS (
                -- Obtener datos de ventas por mes
                SELECT 
                    DATE_TRUNC('month', edo.fe_activacion_estatus - INTERVAL '2 days') AS in_elmes,
                    STRING_AGG(appl1.co_aplicacion::text, ', ') AS co_aplicaciones,
                    COUNT(*) AS Ventas, 
                    SUM(appl1.nu_precio_total) AS Monto
                FROM c001t_aplicaciones appl1
                INNER JOIN equipo e ON appl1.co_usuario = e.co_usuario
                INNER JOIN aplicaciones_distintas edo ON appl1.co_aplicacion = edo.co_aplicacion
                WHERE appl1.co_estatus_aplicacion <> 372
                --AND edo.fe_activacion_estatus BETWEEN (SELECT inicio FROM valores) AND (SELECT final FROM valores)
                $rangoConsulta
                GROUP BY in_elmes
            )
            SELECT
                CASE EXTRACT(MONTH FROM m.mes) 
                    WHEN 1 THEN 'ENE' WHEN 2 THEN 'FEB' WHEN 3 THEN 'MAR' WHEN 4 THEN 'ABR' WHEN 5 THEN 'MAY' 
                    WHEN 6 THEN 'JUN' WHEN 7 THEN 'JUL' WHEN 8 THEN 'AGO' WHEN 9 THEN 'SEP' WHEN 10 THEN 'OCT' 
                    WHEN 11 THEN 'NOV' WHEN 12 THEN 'DIC' 
                END AS meses, 
                EXTRACT(YEAR FROM m.mes) AS Anyo,
                v.co_aplicaciones AS Proyectos, 
                COALESCE(v.Monto, 0) AS Monto,
                COALESCE(v.Ventas, 0) AS Cantidad
            FROM losmeses m
            LEFT JOIN ventastodas v ON DATE_TRUNC('month', m.mes) = v.in_elmes
            ORDER BY EXTRACT(YEAR FROM m.mes), EXTRACT(MONTH FROM m.mes);";
            
           $data = DB::select($sql);
           
            if(count($data) > 0){
                foreach($data as $item){
                    if($item->monto == null)
                        $item->monto = 0;
                }
            }
            return $data;
    }   

    public function getDataBar(Request $request)
    {
        $aplicaciones = $this->getData($request);
        $fechasApp = $this->getRango($request);        
            $meses = [];
            $data = [];
            $leyenda = "Sin información para mostrar"; 
                               
            $type = $request->type ? $request->type : "1"; 
            
            if (count($aplicaciones) > 0) {                   
                foreach ($aplicaciones as $key => $value) {
                    if($value->monto == null)
                        $value->monto  = 0;
                    $meses[$key] = $value->meses.'-'.$value->anyo;
                    if(strcasecmp($type,"1")==0)
                        $data[$key] = $value->cantidad;
                    elseif(strcasecmp($type,"2")==0)
                        $data[$key] = $value->monto;
                }
                $leyenda = 'Aplicaciones Instaladas por Mes';
            } 
            
            return array(
                'aplicaciones' => $aplicaciones,
                'data' => $data,
                'meses' => $meses,
                'leyenda' => $leyenda,        
                'type' => $type,
                'startDate' => $fechasApp['startDate'],
                'endDate' => $fechasApp['endDate'],
            );
    }
        
}