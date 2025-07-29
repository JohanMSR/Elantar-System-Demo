<?php

namespace App\Http\Controllers\Dashboard;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class TeamController extends Controller
{
    
    public function getData(Request $request){
        $orderBy = " ORDER BY edoap.in_orden, ap1.co_aplicacion DESC";
        if($request->has('order') && !empty($request->input('order'))){
            $order = $request->input('order');
            if($order == "1")
                $orderBy = " ORDER BY ap1.co_aplicacion DESC";
            if($order == "2")
                $orderBy = " ORDER BY ap1.fe_creacion DESC";
                if($order == "3")
                $orderBy = " ORDER BY ap1.fe_ultima_mod DESC";
        }
        
        $co_usuario_logueado = Auth::id();        
        
        $query = "
            WITH notificaciones_no_vistas AS (
                SELECT 
                    co_aplicacion,
                    co_usuario,
                    COUNT(*) AS cantidad_no_vistas
                FROM c036t_usuarios_notificacion_his
                WHERE bo_visto = false 
                AND co_tiponoti IN (1,6,7)
                AND co_aplicacion IS NOT NULL 
                AND co_usuario = :co_usuario_logueado 
                GROUP BY co_aplicacion, co_usuario
            ),
            notificaciones_vistas AS (
                SELECT 
                    co_aplicacion,
                    co_usuario,
                    COUNT(*) AS cantidad_vistas
                FROM c036t_usuarios_notificacion_his
                WHERE bo_visto = true 
                AND co_tiponoti IN (1,6,7)
                AND co_aplicacion IS NOT NULL 
                AND co_usuario = :co_usuario_logueado
                GROUP BY co_aplicacion, co_usuario
            )
            SELECT 
                ap1.co_aplicacion, 
                COALESCE(noti_nv.cantidad_no_vistas, 0) AS notifications_not_seen,
                COALESCE(noti_v.cantidad_vistas,     0) AS notifications_seen,
                c1.tx_primer_nombre || ' ' || c1.tx_primer_apellido AS \"Primary Customer Full Name\", 
                c1.tx_email AS \"Primary Customer Email\",
                c1.tx_direccion1 AS \"Address: Street 1\",
                est1.tx_nombre AS \"Address: State/Region\",
                u1.tx_primer_nombre || ' ' || u1.tx_primer_apellido AS \"Primary Analyst\",
                u2.tx_primer_nombre || ' ' || u2.tx_primer_apellido AS \"Secondary Analyst\",
                TO_CHAR(ap1.fe_creacion, 'MM/DD/YY') AS \"Date Created\",
                edoap.tx_nombre AS \"Estado\",
                c1.tx_primer_nombre AS \"Primary Customer First Name\",
                c1.tx_primer_apellido AS \"Primary Customer Last Name\",
                c2.tx_primer_nombre AS \"Secondary Customer First Name\",
                c2.tx_primer_apellido AS \"Secondary Customer Last Name\",
                ap1.tx_ref1_nom AS \"Reference 1 Name\",
                ap1.tx_ref2_nom AS \"Reference 2 Name\",
                ap1.nu_precio_total AS \"Total System Price\",
                ap1.tx_hipoteca_tiempo AS \"How Long Here\",
                ap1.co_qb_id_proyecto AS \"Record ID\",
                TO_CHAR(ap1.fe_ultima_mod, 'MM/DD/YY') AS \"Date Updated\"
            FROM c001t_aplicaciones AS ap1
            LEFT JOIN notificaciones_no_vistas AS noti_nv 
            ON noti_nv.co_aplicacion = ap1.co_aplicacion 
            LEFT JOIN notificaciones_vistas AS noti_v 
            ON noti_v.co_aplicacion = ap1.co_aplicacion 
            INNER JOIN public.c003t_clientes AS c1 ON (c1.co_cliente = ap1.co_cliente)
            LEFT JOIN public.i010t_estados AS est1 ON (est1.co_estado = c1.co_estado)
            LEFT JOIN public.c002t_usuarios AS u1 ON (u1.co_usuario = ap1.co_usuario)
            LEFT JOIN public.c002t_usuarios AS u2 ON (u2.co_usuario = ap1.co_usuario_2)
            LEFT JOIN public.i001t_estatus_aplicaciones AS edoap ON (edoap.co_estatus_aplicacion = ap1.co_estatus_aplicacion)
            LEFT JOIN public.c003t_clientes AS c2 ON (c2.co_cliente = ap1.co_cliente2)
        ";

        $select_filter = "";
        if($request->has('select_filter') && !empty($request->input('select_filter'))){
            $select_filter = $request->input('select_filter'); 
            $query .= "WHERE ap1.co_estatus_aplicacion = $select_filter";            
        }

        
        $query .= $orderBy;

        $teamData = DB::select($query, ['co_usuario_logueado' => $co_usuario_logueado]);
        $data = collect($teamData);
        return $data;
    }
    public function showTeamData(Request $request)
    {        
        $status_app = $this->getStateApp();
        $teamData = $this->getData($request);
        $perPage = 50; 
        $currentPage = $request->get('page', 1);
        $total = $teamData->count();

        $paginatedData = new LengthAwarePaginator(
            $teamData->forPage($currentPage, $perPage),
            $total,
            $perPage,
            $currentPage,
            ['path' => $request->url(), 'query' => $request->query()]
        );        
        return view('dashboard.team.team', compact('paginatedData', 'status_app'));
    }

    public function showTeamDataOrder(Request $request)
    {
        $status_app = $this->getStateApp();
        $teamData = $this->getData($request);
        $perPage = 10; 
        $currentPage = $request->get('page', 1);
        $total = $teamData->count();
        $select_filter = $request->input('select_filter') ?? '';
        $order = $request->input('order') ?? '';
        $paginatedData = new LengthAwarePaginator(
            $teamData->forPage($currentPage, $perPage),
            $total,
            $perPage,
            $currentPage,
            ['path' => $request->url(), 'query' => $request->except('page')]
        );

        return view('dashboard.team.team', compact('paginatedData','status_app','select_filter','order'));
    }

    public function getDataSearch(Request $request){
        $search = $request->input('search');
        $search = Str::lower($search);
        // Dividir el texto de búsqueda en palabras individuales
        $searchWords = explode(' ', $search);
        
        $co_usuario_logueado = Auth::id();
        $query = "
            WITH notificaciones_no_vistas AS (
                SELECT 
                    co_aplicacion,
                    co_usuario,
                    COUNT(*) AS cantidad_no_vistas
                FROM c036t_usuarios_notificacion_his
                WHERE bo_visto = false 
                AND co_tiponoti IN (1,6,7) 
                AND co_aplicacion IS NOT NULL  
                AND co_usuario = '$co_usuario_logueado'
                GROUP BY co_aplicacion, co_usuario
            ),
            notificaciones_vistas AS (
                SELECT 
                    co_aplicacion,
                    co_usuario,
                    COUNT(*) AS cantidad_vistas
                FROM c036t_usuarios_notificacion_his
                WHERE bo_visto = true 
                    AND co_tiponoti IN (1,6,7) 
                    AND co_aplicacion IS NOT NULL  
                    AND co_usuario = '$co_usuario_logueado'
                GROUP BY co_aplicacion, co_usuario
            )
            SELECT 
            ap1.co_aplicacion, 
            COALESCE(noti_nv.cantidad_no_vistas, 0) AS notifications_not_seen,
            COALESCE(noti_v.cantidad_vistas, 0) AS notifications_seen,
            c1.tx_primer_nombre || ' ' || c1.tx_primer_apellido AS \"Primary Customer Full Name\", 
            c1.tx_email AS \"Primary Customer Email\",
            c1.tx_direccion1 AS \"Address: Street 1\",
            c1.tx_direccion2 AS \"Address: Street 2\",
            est1.tx_nombre AS \"Address: State/Region\",
            u1.tx_primer_nombre || ' ' || u1.tx_primer_apellido AS \"Primary Analyst\",
            u2.tx_primer_nombre || ' ' || u2.tx_primer_apellido AS \"Secondary Analyst\",
            TO_CHAR(ap1.fe_creacion, 'MM/DD/YYYY') AS \"Date Created\",
            edoap.tx_nombre AS \"Estado\",
            c1.tx_primer_nombre AS \"Primary Customer First Name\",
            c1.tx_primer_apellido AS \"Primary Customer Last Name\",
            c2.tx_primer_nombre AS \"Secondary Customer First Name\",
            c2.tx_primer_apellido AS \"Secondary Customer Last Name\",
            ap1.tx_ref1_nom AS \"Reference 1 Name\",
            ap1.tx_ref2_nom AS \"Reference 2 Name\",
            ap1.nu_precio_total AS \"Total System Price\",
            ap1.tx_hipoteca_tiempo AS \"How Long Here\",
            ap1.co_qb_id_proyecto AS \"Record ID\",
            TO_CHAR(ap1.fe_ultima_mod, 'MM/DD/YYYY') AS \"Date Updated\"
            FROM c001t_aplicaciones AS ap1
            LEFT JOIN notificaciones_no_vistas AS noti_nv 
            ON noti_nv.co_aplicacion = ap1.co_aplicacion
            LEFT JOIN notificaciones_vistas AS noti_v 
            ON noti_v.co_aplicacion = ap1.co_aplicacion
            INNER JOIN public.c003t_clientes AS c1 ON (c1.co_cliente = ap1.co_cliente)
            LEFT JOIN public.i010t_estados AS est1 ON (est1.co_estado = c1.co_estado)
            LEFT JOIN public.c002t_usuarios AS u1 ON (u1.co_usuario = ap1.co_usuario)
            LEFT JOIN public.c002t_usuarios AS u2 ON (u2.co_usuario = ap1.co_usuario_2)
            LEFT JOIN public.i001t_estatus_aplicaciones AS edoap ON (edoap.co_estatus_aplicacion = ap1.co_estatus_aplicacion)
            LEFT JOIN public.c003t_clientes AS c2 ON (c2.co_cliente = ap1.co_cliente2)
            WHERE 
            (
                " . implode(' OR ', array_map(function($word) {
                    return "(
                        unaccent(LOWER(c1.tx_primer_nombre)) LIKE unaccent(LOWER('%$word%')) OR
                        unaccent(LOWER(c1.tx_primer_apellido)) LIKE unaccent(LOWER('%$word%')) OR
                        unaccent(LOWER(c2.tx_primer_nombre)) LIKE unaccent(LOWER('%$word%')) OR
                        unaccent(LOWER(c2.tx_primer_apellido)) LIKE unaccent(LOWER('%$word%')) OR
                        unaccent(LOWER(c1.tx_direccion1)) LIKE unaccent(LOWER('%$word%')) OR
                        unaccent(LOWER(c1.tx_direccion2)) LIKE unaccent(LOWER('%$word%')) OR
                        CAST(ap1.co_aplicacion AS TEXT) LIKE '%$word%'
                    )";
                }, $searchWords)) . "
            )
            ORDER BY edoap.in_orden, ap1.fe_creacion DESC";

        $teamData = DB::select($query);
        $data = collect($teamData);
        
        return $data;
    }
    public function showTeamSearch(Request $request)
    {
        $validated = $request->validate([
            'search' => 'required|string|min:1'
        ], [
            'search.required' => 'El campo de búsqueda es obligatorio.',
            'search.string' => 'El campo de búsqueda debe ser un texto.',
            'search.min' => 'El campo de búsqueda debe tener al menos 1 caracter.'
        ]);        
        $status_app = $this->getStateApp();
        $teamData = $this->getDataSearch($request);
        
        $perPage = 10; 
        $currentPage = $request->get('page', 1);
        $total = $teamData->count();

        $paginatedData = new LengthAwarePaginator(
            $teamData->forPage($currentPage, $perPage),
            $total,
            $perPage,
            $currentPage,
            ['path' => $request->url(), 'query' => $request->except('page')]
        );
        
        return view('dashboard.team.team', compact('paginatedData','status_app'));
    }
    
    public function showTeamFilter(Request $request)
    {
        
        $validated = $request->validate([
            'select_filter' => 'required|integer'
        ], [
            'select_filter.required' => 'El campo filtro es obligatorio.',
            'select_filter.integer' => 'El campo filtro debe ser un número entero.'
        ]);

        $select_filter = $request->input('select_filter');
        $order = $request->input('order') ?? '';
        $status_app = $this->getStateApp();        
        $teamData = $this->getData($request);
        $perPage = 50; 
        $currentPage = $request->get('page', 1);
        $total = $teamData->count();

        $paginatedData = new LengthAwarePaginator(
            $teamData->forPage($currentPage, $perPage),
            $total,
            $perPage,
            $currentPage,
            ['path' => $request->url(), 'query' => $request->query()]
        );        
        return view('dashboard.team.team', compact('paginatedData', 'status_app','select_filter','order'));
        
        
    }

    public function getStateApp(){
        $status = DB::table('i001t_estatus_aplicaciones')
        ->select('co_estatus_aplicacion', 'tx_nombre')
        ->whereNotIn('co_estatus_aplicacion', [373, 0])
        ->orderBy('in_orden', 'asc')
        ->get();
        return $status;
    }

}