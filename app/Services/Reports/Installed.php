<?php

namespace App\Services\Reports;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Illuminate\Support\Number;
use Illuminate\Support\Facades\DB;

class Installed extends Model 
{
    use HasFactory;
  
    public function getRango(Request $request)
    {
        $startDate = $request->startDate ?? "";
        $endDate = $request->endDate ?? "";
        
        $today = Carbon::now()->format('Y-m-d');
        $firstDay = Carbon::now()->startOfMonth()->format('Y-m-d');        
        //$rangoConsulta = " (DATE(edo.fe_activacion_estatus) BETWEEN DATE('$firstDay') AND DATE('$today'))";
        $rangoConsulta = "ad.fe_activacion_estatus >= DATE_TRUNC('month', CURRENT_DATE) + INTERVAL '2 days'
        AND ad.fe_activacion_estatus < DATE_TRUNC('month', CURRENT_DATE) + INTERVAL '1 month' + INTERVAL '2 days'";

        if(!empty($startDate) && !empty($endDate)){
            $timestamp = strtotime($startDate);
            $startDate = date("Y-m-d", $timestamp);
            $timestamp1 = strtotime($endDate);
            $endDate = date("Y-m-d", $timestamp1);                    
           $rangoConsulta = " DATE(ad.fe_activacion_estatus) >= DATE('$startDate') AND DATE(ad.fe_activacion_estatus) < DATE('$endDate')";
        }    
        if(!empty($startDate) && empty($endDate)){
            $timestamp = strtotime($startDate);
            $startDate = date("Y-m-d", $timestamp);
            $endDate = Carbon::parse($startDate)->addMonths(1);
            $endDate = $endDate->format('Y-m-d');                        
            $rangoConsulta = " DATE(ad.fe_activacion_estatus) >= DATE('$startDate') AND DATE(ad.fe_activacion_estatus) < DATE('$endDate')";
        }            
        if(empty($startDate) && !empty($endDate)){
            $timestamp = strtotime($endDate);
            $endDate = date("Y-m-d", $timestamp);
            $startDate = Carbon::parse($endDate)->subMonths(1);
            $startDate = $startDate->format('Y-m-d');                        
            $rangoConsulta = " DATE(ad.fe_activacion_estatus) >= DATE('$startDate') AND DATE(ad.fe_activacion_estatus) < DATE('$endDate')";
        }
        if(empty($startDate) && empty($endDate)){
            $startDate = $firstDay;
            $endDate =  $today;
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
        $co_usuario_logueado = Auth()->id();
        $rangoConsulta = $rango['rangoConsulta'];
        $analista = $request->code_analyst ?? 0;
        
        $sql = "
        WITH RECURSIVE equipo AS (
                -- Construcción del equipo incluyendo el líder y sus dependientes
                SELECT co_usuario FROM c002t_usuarios WHERE co_usuario = :co_usuario_logueado
                UNION ALL
                SELECT c.co_usuario FROM c002t_usuarios c 
                INNER JOIN equipo e ON c.co_usuario_padre = e.co_usuario
            ), 
            aplicaciones_distintas AS (
                -- Identificar aplicaciones únicas que hayan pasado por el estatus 366
                SELECT DISTINCT ON (co_aplicacion) 
                co_aplicacion, fe_activacion_estatus
                FROM c026t_aplicaciones_estatus_historial
                WHERE co_estatus_aplicacion = 366
                ORDER BY co_aplicacion, fe_activacion_estatus
            )
            SELECT 
                a.co_usuario,
                u.tx_primer_nombre || ' ' || u.tx_primer_apellido AS analista,
                COUNT(a.co_aplicacion) AS ventas,
                SUM(a.nu_precio_total) AS monto,
                STRING_AGG(a.co_aplicacion::text, ', ') AS co_aplicaciones,
                    o.tx_nombre AS oficina
            FROM c001t_aplicaciones a
            JOIN aplicaciones_distintas ad ON a.co_aplicacion = ad.co_aplicacion
            INNER JOIN equipo eq ON a.co_usuario = eq.co_usuario
            INNER JOIN c002t_usuarios u ON a.co_usuario = u.co_usuario
            INNER JOIN c012t_usuarios_oficinas uo ON a.co_usuario = uo.co_usuario
            INNER JOIN i008t_oficinas o ON uo.co_oficina = o.co_oficina
            --WHERE ad.fe_activacion_estatus >= date('2025-02-01') AND  ad.fe_activacion_estatus <= date('2025-02-03')
            WHERE $rangoConsulta
            AND a.co_estatus_aplicacion <> 372";
       
        if($analista != 0) {
            $sql .= " AND a.co_usuario = :analista";
        }

        $sql .= " GROUP BY a.co_usuario, u.tx_primer_nombre, u.tx_primer_apellido, o.tx_nombre
         ORDER BY ventas DESC, monto DESC;";
        
         $params = [
            'co_usuario_logueado' => $co_usuario_logueado,            
        ];
        

        if($analista != 0) {
            $params['analista'] = $analista;
        }
        
        return DB::select($sql, $params);
    }        
}