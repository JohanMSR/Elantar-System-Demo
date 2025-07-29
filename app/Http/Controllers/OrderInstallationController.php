<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\OrderInstallationRequest;
use App\Http\Requests\OrderInstallationShowRequest;
use Illuminate\Support\Facades\Log;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Exports\OrderInstallationExport;
use Maatwebsite\Excel\Facades\Excel;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Session;
use App\Models\C002tUsuario;
use App\Http\Requests\OrderInstallationUpdateRequest;
use App\Models\C038tOrdenes;
use App\Models\C001tAplicacione;
use App\Models\C039tOrdenGasto;

class OrderInstallationController extends Controller
{
    
    public function index(OrderInstallationRequest $request)
    {
        $userId = Auth::id();
        $typeUser = $this->getUserType($userId);
        if($typeUser == 'user_system'){
            return back()
                ->with('error', 'No tiene permisos para acceder a Ordenes de Instalacion');
        }
        if($typeUser == 'administrative'){
            $ordenes = $this->getDataAdministrative($request);
        }
        if($typeUser == 'office_manager' || $typeUser == 'installer'){
            $ordenes = $this->getDataOfficeInstaller($request);
        }

        $status_ordenes = DB::select('SELECT * FROM i022t_estatus_orden order by co_estatus_orden');
        $perPage = 50; 
        $currentPage = $request->get('page', 1);
        $total = $ordenes->count();

        $paginatedData = new LengthAwarePaginator(
            $ordenes->forPage($currentPage, $perPage),
            $total,
            $perPage,
            $currentPage,
            ['path' => $request->url(), 'query' => $request->query()]
        );        
        
        return view('dashboard.ordenes-instalacion.index', compact('paginatedData', 'status_ordenes'));
       
    }

    /* Return order data for administrative user */
    public function getDataAdministrative(OrderInstallationRequest $request)
    {
        $userId = Auth::id();

        if (!$userId) {
            return collect();
        }
        $sql = "WITH notificaciones_no_vistas AS (
            SELECT 
                co_aplicacion,
                co_usuario,
                COUNT(*) AS cantidad_no_vistas
            FROM c036t_usuarios_notificacion_his
            WHERE bo_visto = false 
                AND co_tiponoti IN (9,10)
                AND co_aplicacion IS NOT NULL 
                AND co_usuario = :co_usuario
            GROUP BY co_aplicacion, co_usuario
        ),
        notificaciones_vistas AS (
            SELECT 
                co_aplicacion,
                co_usuario,
                COUNT(*) AS cantidad_vistas
            FROM c036t_usuarios_notificacion_his
            WHERE bo_visto = true 
                AND co_tiponoti IN (9,10)
                AND co_aplicacion IS NOT NULL 
                AND co_usuario = :co_usuario   -- aqui va el co_usuario_logueado para las notificaciones
            GROUP BY co_aplicacion, co_usuario
        ),
        gastos_por_orden AS (
            SELECT 
                co_orden, 
                SUM(nu_amount) AS total_gastos_orden
            FROM c046t_expense_by_io             
            GROUP BY co_orden
        )
        SELECT 
            ord.co_orden, 
            cli.co_cliente,
            cli.tx_primer_nombre   AS tx_primer_nombre_cliente,
            cli.tx_primer_apellido AS tx_primer_apellido_cliente,
            cli.tx_telefono,
            ord.co_aplicacion,
            ap.co_estatus_aplicacion,
            TO_CHAR(ap.fe_instalacion, 'YYYY-MM-DD') AS fecha_instalacion,
            ord.co_estatus_orden,
            est.tx_estatus_orden AS estatus_de_la_orden,
            CASE 
                WHEN ord.bo_accion IS TRUE THEN 'INSTALAR'
                WHEN ord.bo_accion IS FALSE THEN 'DESINSTALAR'
                ELSE 'SIN DEFINIR'
            END AS accion,
            TO_CHAR(ord.fe_registro, 'YYYY-MM-DD HH24:MI') AS fecha_registro,
            ord.co_manager,
            uman.tx_primer_nombre AS tx_primer_nombre_manager,
            uman.tx_primer_apellido AS tx_primer_apellido_manager,
            ord.co_plomero,
            uplo.tx_primer_nombre AS primer_nombre_plomero,
            uplo.tx_primer_apellido AS primer_apellido_plomero,
            COALESCE(gpo.total_gastos_orden, 0) AS total_gastos_orden,
            COALESCE(noti_nv.cantidad_no_vistas, 0) AS notific_no_vistas,
            COALESCE(noti_v.cantidad_vistas, 0) AS notific_vistas
        FROM c038t_ordenes AS ord
        LEFT JOIN notificaciones_no_vistas AS noti_nv 
            ON noti_nv.co_aplicacion = ord.co_aplicacion
        LEFT JOIN notificaciones_vistas AS noti_v 
            ON noti_v.co_aplicacion = ord.co_aplicacion
        LEFT JOIN gastos_por_orden AS gpo
            ON gpo.co_orden = ord.co_orden
        LEFT JOIN i022t_estatus_orden AS est 
            ON est.co_estatus_orden = ord.co_estatus_orden
        LEFT JOIN c002t_usuarios AS uplo 
            ON uplo.co_usuario = ord.co_plomero
        LEFT JOIN c002t_usuarios AS uman 
            ON uman.co_usuario = ord.co_manager
        LEFT JOIN c001t_aplicaciones AS ap 
            ON ap.co_aplicacion = ord.co_aplicacion
        LEFT JOIN c003t_clientes AS cli 
            ON cli.co_cliente = ap.co_cliente
        LEFT JOIN i010t_estados AS estcli 
            ON estcli.co_estado = cli.co_estado
        ";
        $bandFecha = false;
        if($request->query('fechaInicio') && $request->query('fechaFin')){
            $fecha_inicio = $request->query('fechaInicio');
            $fecha_inicio = Carbon::createFromFormat('m/d/Y', $fecha_inicio)->format('Y-m-d');
            $fecha_final = $request->query('fechaFin');
            $fecha_final = Carbon::createFromFormat('m/d/Y', $fecha_final)->format('Y-m-d');
            $sql .= " WHERE (ap.fe_instalacion BETWEEN '$fecha_inicio' AND '$fecha_final')";
            $bandFecha = true;
        }
        
        if($request->query('co_estatus_orden')){
            $co_estatus_orden = $request->query('co_estatus_orden');
            if($bandFecha){
                $sql .= " AND ord.co_estatus_orden = $co_estatus_orden";
            }else{
                $sql .= " WHERE ord.co_estatus_orden = $co_estatus_orden";
            }
        }       


        $order = $request->query('order') ?? 1;
        
        if($order == 1){
            $sql .= " ORDER BY ord.co_orden DESC;";
        }else if($order == 2){
            $sql .= " ORDER BY ap.fe_instalacion DESC;";
        }else if($order == 3){
            $sql .= " ORDER BY ord.co_estatus_orden DESC;";
        }
        $data = collect(DB::select($sql, [
           'co_usuario' => $userId       
        ]));
        return $data;
    }

    /* Return order data for office manager and installer */
    public function getDataOfficeInstaller(OrderInstallationRequest $request)
    {
        $userId = Auth::id();

        if (!$userId) {
            return collect();
        }
        $sql = "WITH notificaciones_no_vistas AS (
            SELECT 
                co_aplicacion,
                co_usuario,
                COUNT(*) AS cantidad_no_vistas
            FROM c036t_usuarios_notificacion_his
            WHERE bo_visto = false 
                AND co_tiponoti IN (9,10)
                AND co_aplicacion IS NOT NULL 
                AND co_usuario = :co_usuario -- aqui va el co_usuario_logueado para las notificaciones
            GROUP BY co_aplicacion, co_usuario
        ),
        notificaciones_vistas AS (
            SELECT 
                co_aplicacion,
                co_usuario,
                COUNT(*) AS cantidad_vistas
            FROM c036t_usuarios_notificacion_his
            WHERE bo_visto = true 
                AND co_tiponoti IN (9,10)
                AND co_aplicacion IS NOT NULL 
                AND co_usuario = :co_usuario   -- aqui va el co_usuario_logueado para las notificaciones
            GROUP BY co_aplicacion, co_usuario
        ),
        gastos_por_orden AS (
            SELECT 
                co_orden, 
                SUM(nu_amount) AS total_gastos_orden
            FROM c046t_expense_by_io             
            GROUP BY co_orden
        )
        SELECT 
            ord.co_orden, 
            cli.co_cliente,
            cli.tx_primer_nombre   AS tx_primer_nombre_cliente,
            cli.tx_primer_apellido AS tx_primer_apellido_cliente,
            cli.tx_telefono,
            ord.co_aplicacion,
            ap.co_estatus_aplicacion,
            TO_CHAR(ap.fe_instalacion, 'YYYY-MM-DD') AS fecha_instalacion,
            ord.co_estatus_orden,
            est.tx_estatus_orden AS estatus_de_la_orden,
            CASE 
                WHEN ord.bo_accion IS TRUE THEN 'INSTALAR'
                WHEN ord.bo_accion IS FALSE THEN 'DESINSTALAR'
                ELSE 'SIN DEFINIR'
            END AS accion,
            TO_CHAR(ord.fe_registro, 'YYYY-MM-DD HH24:MI') AS fecha_registro,
            ord.co_manager,
            uman.tx_primer_nombre AS tx_primer_nombre_manager,
            uman.tx_primer_apellido AS tx_primer_apellido_manager,
            ord.co_plomero,
            uplo.tx_primer_nombre AS primer_nombre_plomero,
            uplo.tx_primer_apellido AS primer_apellido_plomero,
            COALESCE(gpo.total_gastos_orden, 0) AS total_gastos_orden,
            COALESCE(noti_nv.cantidad_no_vistas, 0) AS notific_no_vistas,
            COALESCE(noti_v.cantidad_vistas, 0) AS notific_vistas
        FROM c038t_ordenes AS ord
        LEFT JOIN notificaciones_no_vistas AS noti_nv 
            ON noti_nv.co_aplicacion = ord.co_aplicacion
        LEFT JOIN notificaciones_vistas AS noti_v 
            ON noti_v.co_aplicacion = ord.co_aplicacion
        LEFT JOIN gastos_por_orden AS gpo
            ON gpo.co_orden = ord.co_orden
        LEFT JOIN i022t_estatus_orden AS est 
            ON est.co_estatus_orden = ord.co_estatus_orden
        LEFT JOIN c002t_usuarios AS uplo 
            ON uplo.co_usuario = ord.co_plomero
        LEFT JOIN c002t_usuarios AS uman 
            ON uman.co_usuario = ord.co_manager
        LEFT JOIN c001t_aplicaciones AS ap 
            ON ap.co_aplicacion = ord.co_aplicacion
        LEFT JOIN c003t_clientes AS cli 
            ON cli.co_cliente = ap.co_cliente
        LEFT JOIN i010t_estados AS estcli 
            ON estcli.co_estado = cli.co_estado
        WHERE 
        ((ord.co_plomero = :co_usuario) or (ord.co_manager = :co_usuario))  -- co_usuario_logueado         
        ";
        
        if($request->query('fechaInicio') && $request->query('fechaFin')){
            $fecha_inicio = $request->query('fechaInicio');
            $fecha_inicio = Carbon::createFromFormat('m/d/Y', $fecha_inicio)->format('Y-m-d');
            $fecha_final = $request->query('fechaFin');
            $fecha_final = Carbon::createFromFormat('m/d/Y', $fecha_final)->format('Y-m-d');
            $sql .= " AND (ap.fe_instalacion BETWEEN '$fecha_inicio' AND '$fecha_final')";                        
        }

        if($request->query('co_estatus_orden')){
            $co_estatus_orden = $request->query('co_estatus_orden');
            $sql .= " AND ord.co_estatus_orden = $co_estatus_orden";            
        }
        
        $order = $request->query('order') ?? 1;
        
        if($order == 1){
            $sql .= " ORDER BY ord.co_orden DESC;";
        }else if($order == 2){
            $sql .= " ORDER BY ap.fe_instalacion DESC;";
        }else if($order == 3){
            $sql .= " ORDER BY ord.co_estatus_orden DESC;";
        }
        
        $data = collect(DB::select($sql, [
            'co_usuario' => $userId       
         ]));
         return $data;
        
    }

    /* Return order data for administrative user */
    public function getDetails($co_orden)
    {
        $userId = Auth::id();

        if (!$userId) {
            return collect();
        }

        $order = DB::table('c038t_ordenes as o')
            ->leftJoin('c001t_aplicaciones as a', 'o.co_aplicacion', '=', 'a.co_aplicacion')
            ->select('o.*', 'a.co_tipo_agua', 'a.fe_instalacion', 'a.co_estatus_aplicacion')
            ->where('o.co_orden', $co_orden)
            ->first();

        $expenses = DB::table('c046t_expense_by_io as ebyio')
            ->leftJoin('i023t_expense as e', 'ebyio.co_expense', '=', 'e.id')
            ->select('ebyio.*', 'e.name', 'e.estimated_cost')
            ->where('ebyio.co_orden', $co_orden)
            ->get();

        $verifications = DB::table('c045t_verification_by_io as vbyio')
            ->leftJoin('i027t_verification as v', 'vbyio.co_verification', '=', 'v.id')
            ->select('vbyio.*', 'v.name')
            ->where('vbyio.co_orden', $co_orden)
            ->get();

        // Combine as needed:
        $order->expenses = $expenses;
        $order->verifications = $verifications;
        

        return $order;
    }

    public function show(OrderInstallationShowRequest $request)
    {
        $co_orden = $request->query('co_orden');
    
        // Buscar la orden por su id
        $orden = $this->getDetails($co_orden);
    
        if (!$orden) {
            return response()->json(['error' => 'Orden no encontrada'], 404);
        }
        $manager = $this->getManager();
        $installers = $this->getInstallers();
        $expenses = $this->getExpenses($orden->co_tipo_agua);
        $verification = $this->getVerification($orden->co_tipo_agua);
    
        return response()->json(
            [
                'orders' => $orden,
                'managers' => $manager,
                'installers' => $installers,
                'expenses' => $expenses,
                'verification' => $verification
            ]); 
    }

    private function getExpenses($co_tipo_agua){
        return DB::select("
            SELECT 
                ebtw.id, 
                ebtw.expense_id, 
                ebtw.co_tipo_agua, 
                e.name,
                e.description,
                e.is_active,
                e.estimated_cost
            FROM 
                i024t_expense_by_water_type AS ebtw
            INNER JOIN 
                i023t_expense AS e
                ON ebtw.expense_id = e.id
            WHERE 
                ebtw.co_tipo_agua = ?
                AND e.is_active = true
        ", [$co_tipo_agua]);
    }

    private function getVerification($co_tipo_agua){
        return DB::table('i028t_verification_by_water_type as vbwt')
            ->join('i027t_verification as v', 'vbwt.verification_id', '=', 'v.id')
            ->where('vbwt.co_tipo_agua', '=', $co_tipo_agua)
            ->where('v.is_active', '=', true)
            ->select(
                'vbwt.id',
                'vbwt.verification_id',
                'vbwt.co_tipo_agua',
                'v.name',
                'v.description',
                'v.is_active'
            )
            ->get();
    }

    private function getUserType($userId)
    {
        if (!$userId) {
            Log::error(['error' => 'Usuario no autenticado']);
        }

        // Consultar el tipo de usuario en la tabla c002t_usuarios
        $user = DB::table('c002t_usuarios')->where('co_usuario', $userId)->first();

        if (!$user) {
            Log::error(['error' => 'Usuario no encontrado']);
        }

        // Verificar si es administrativo
        if ($user->co_tipo_usuario == 3) {
            return 'administrative';
        }
        
        // Verificar si es office manager (aparece como co_manager en alguna orden)
        $isManager = DB::table('c038t_ordenes')->where('co_manager', $userId)->exists();

        if ($isManager) {
            return 'office_manager';
        }

        // Verificar si es plomero
        if ($user->co_tipo_usuario == 2) {
            return 'installer';
        }        

        // Si no coincide con ninguno
        return 'user_system';
    }

    public function getDataSearchAdministrative(Request $request){
        $search = $request->input('search');
        $search = Str::lower($search);
        // Dividir el texto de búsqueda en palabras individuales
        $searchWords = explode(' ', $search);
        
        $userId = Auth::id();
        $query = "WITH notificaciones_no_vistas AS (
                SELECT 
                    co_aplicacion,
                    co_usuario,
                    COUNT(*) AS cantidad_no_vistas
                FROM c036t_usuarios_notificacion_his
                WHERE bo_visto = false 
                    AND co_tiponoti IN (9)
                    AND co_aplicacion IS NOT NULL 
                    AND co_usuario = :co_usuario
                GROUP BY co_aplicacion, co_usuario
            ),
            notificaciones_vistas AS (
                SELECT 
                    co_aplicacion,
                    co_usuario,
                    COUNT(*) AS cantidad_vistas
                FROM c036t_usuarios_notificacion_his
                WHERE bo_visto = true 
                    AND co_tiponoti IN (9)
                    AND co_aplicacion IS NOT NULL 
                    AND co_usuario = :co_usuario
                GROUP BY co_aplicacion, co_usuario
            ),
            gastos_por_orden AS (
                SELECT 
                    co_orden, 
                    SUM(nu_monto_gasto) AS total_gastos_orden
                FROM c039t_orden_gasto
                GROUP BY co_orden
            )
            SELECT 
                ord.co_orden, 
                cli.co_cliente,
                cli.tx_primer_nombre   AS tx_primer_nombre_cliente,
                cli.tx_primer_apellido AS tx_primer_apellido_cliente,
                cli.tx_telefono,
                ord.co_aplicacion,
                ap.co_estatus_aplicacion,
                TO_CHAR(ap.fe_instalacion, 'YYYY-MM-DD') AS fecha_instalacion,
                ord.co_estatus_orden,
                est.tx_estatus_orden AS estatus_de_la_orden,
                CASE 
                    WHEN ord.bo_accion IS TRUE THEN 'INSTALAR'
                    WHEN ord.bo_accion IS FALSE THEN 'DESINSTALAR'
                    ELSE 'SIN DEFINIR'
                END AS accion,
                TO_CHAR(ord.fe_registro, 'YYYY-MM-DD HH24:MI') AS fecha_registro,
                ord.co_manager,
                uman.tx_primer_nombre AS tx_primer_nombre_manager,
                uman.tx_primer_apellido AS tx_primer_apellido_manager,
                ord.co_plomero,
                uplo.tx_primer_nombre AS primer_nombre_plomero,
                uplo.tx_primer_apellido AS primer_apellido_plomero,
                COALESCE(gpo.total_gastos_orden, 0) AS total_gastos_orden,
                COALESCE(noti_nv.cantidad_no_vistas, 0) AS notific_no_vistas,
                COALESCE(noti_v.cantidad_vistas, 0) AS notific_vistas
            FROM c038t_ordenes AS ord
            LEFT JOIN notificaciones_no_vistas AS noti_nv 
                ON noti_nv.co_aplicacion = ord.co_aplicacion
            LEFT JOIN notificaciones_vistas AS noti_v 
                ON noti_v.co_aplicacion = ord.co_aplicacion
            LEFT JOIN gastos_por_orden AS gpo
                ON gpo.co_orden = ord.co_orden
            LEFT JOIN i022t_estatus_orden AS est 
                ON est.co_estatus_orden = ord.co_estatus_orden
            LEFT JOIN c002t_usuarios AS uplo 
                ON uplo.co_usuario = ord.co_plomero
            LEFT JOIN c002t_usuarios AS uman 
                ON uman.co_usuario = ord.co_manager
            LEFT JOIN c001t_aplicaciones AS ap 
                ON ap.co_aplicacion = ord.co_aplicacion
            LEFT JOIN c003t_clientes AS cli 
                ON cli.co_cliente = ap.co_cliente
            LEFT JOIN i010t_estados AS estcli 
                ON estcli.co_estado = cli.co_estado
            WHERE 
            (
            " . implode(' OR ', array_map(function($word) {
                return "(
                    unaccent(LOWER(cli.tx_primer_nombre)) LIKE unaccent(LOWER('%$word%')) OR
                    unaccent(LOWER(cli.tx_primer_apellido)) LIKE unaccent(LOWER('%$word%')) OR
                    unaccent(LOWER(uman.tx_primer_nombre)) LIKE unaccent(LOWER('%$word%')) OR
                    unaccent(LOWER(uman.tx_primer_nombre)) LIKE unaccent(LOWER('%$word%')) OR
                    unaccent(LOWER(uplo.tx_primer_nombre)) LIKE unaccent(LOWER('%$word%')) OR
                    unaccent(LOWER(uplo.tx_primer_nombre)) LIKE unaccent(LOWER('%$word%')) OR
                    CAST(ord.co_orden AS TEXT) LIKE '%$word%'
                    )";
                }, $searchWords)) . "
            )
            ORDER BY ord.co_orden DESC;";

        $orderData = DB::select($query, [
            'co_usuario' => $userId
        ]);
        $data = collect($orderData);
        
        return $data;
    }

    private function getDataSearchOfficeInstaller(Request $request){
        $search = $request->input('search');
        $search = Str::lower($search);
        // Dividir el texto de búsqueda en palabras individuales
        $searchWords = explode(' ', $search);
        
        $userId = Auth::id();
        $query = "WITH notificaciones_no_vistas AS (
            SELECT 
                co_aplicacion,
                co_usuario,
                COUNT(*) AS cantidad_no_vistas
            FROM c036t_usuarios_notificacion_his
            WHERE bo_visto = false 
                AND co_tiponoti IN (9,10)
                AND co_aplicacion IS NOT NULL 
                AND co_usuario = :co_usuario -- aqui va el co_usuario_logueado para las notificaciones
            GROUP BY co_aplicacion, co_usuario
        ),
        notificaciones_vistas AS (
            SELECT 
                co_aplicacion,
                co_usuario,
                COUNT(*) AS cantidad_vistas
            FROM c036t_usuarios_notificacion_his
            WHERE bo_visto = true 
                AND co_tiponoti IN (9,10)
                AND co_aplicacion IS NOT NULL 
                AND co_usuario = :co_usuario   -- aqui va el co_usuario_logueado para las notificaciones
            GROUP BY co_aplicacion, co_usuario
        ),
        gastos_por_orden AS (
            SELECT 
                co_orden, 
                SUM(nu_monto_gasto) AS total_gastos_orden
            FROM c039t_orden_gasto
            GROUP BY co_orden
        )
        SELECT 
            ord.co_orden, 
            cli.co_cliente,
            cli.tx_primer_nombre   AS tx_primer_nombre_del_cliente,
            cli.tx_primer_apellido AS tx_primer_apellido_del_cliente,
            cli.tx_telefono,
            ord.co_aplicacion,
            ap.co_estatus_aplicacion,
            TO_CHAR(ap.fe_instalacion, 'YYYY-MM-DD') AS fecha_instalacion,
            ord.co_estatus_orden,
            est.tx_estatus_orden AS estatus_de_la_orden,
            CASE 
                WHEN ord.bo_accion IS TRUE THEN 'INSTALAR'
                WHEN ord.bo_accion IS FALSE THEN 'DESINSTALAR'
                ELSE 'SIN DEFINIR'
            END AS accion,
            TO_CHAR(ord.fe_registro, 'YYYY-MM-DD HH24:MI') AS fecha_registro,
            ord.co_manager,
            uman.tx_primer_nombre AS tx_primer_nombre_del_manager,
            uman.tx_primer_apellido AS tx_primer_apellido_del_manager,
            ord.co_plomero,
            uplo.tx_primer_nombre AS primer_nombre_del_plomero,
            uplo.tx_primer_apellido AS primer_apellido_del_plomero,
            COALESCE(gpo.total_gastos_orden, 0) AS total_gastos_orden,
            COALESCE(noti_nv.cantidad_no_vistas, 0) AS notific_no_vistas,
            COALESCE(noti_v.cantidad_vistas, 0) AS notific_vistas
        FROM c038t_ordenes AS ord
        LEFT JOIN notificaciones_no_vistas AS noti_nv 
            ON noti_nv.co_aplicacion = ord.co_aplicacion
        LEFT JOIN notificaciones_vistas AS noti_v 
            ON noti_v.co_aplicacion = ord.co_aplicacion
        LEFT JOIN gastos_por_orden AS gpo
            ON gpo.co_orden = ord.co_orden
        LEFT JOIN i022t_estatus_orden AS est 
            ON est.co_estatus_orden = ord.co_estatus_orden
        LEFT JOIN c002t_usuarios AS uplo 
            ON uplo.co_usuario = ord.co_plomero
        LEFT JOIN c002t_usuarios AS uman 
            ON uman.co_usuario = ord.co_manager
        LEFT JOIN c001t_aplicaciones AS ap 
            ON ap.co_aplicacion = ord.co_aplicacion
        LEFT JOIN c003t_clientes AS cli 
            ON cli.co_cliente = ap.co_cliente
        LEFT JOIN i010t_estados AS estcli 
            ON estcli.co_estado = cli.co_estado
        WHERE 
        ((ord.co_plomero = :co_usuario) or (ord.co_manager = :co_usuario))
        AND
        (
        " . implode(' OR ', array_map(function($word) {
            return "(
                unaccent(LOWER(cli.tx_primer_nombre)) LIKE unaccent(LOWER('%$word%')) OR
                unaccent(LOWER(cli.tx_primer_apellido)) LIKE unaccent(LOWER('%$word%')) OR
                unaccent(LOWER(uman.tx_primer_nombre)) LIKE unaccent(LOWER('%$word%')) OR
                unaccent(LOWER(uman.tx_primer_nombre)) LIKE unaccent(LOWER('%$word%')) OR
                unaccent(LOWER(uplo.tx_primer_nombre)) LIKE unaccent(LOWER('%$word%')) OR
                unaccent(LOWER(uplo.tx_primer_nombre)) LIKE unaccent(LOWER('%$word%')) OR
                CAST(ord.co_orden AS TEXT) LIKE '%$word%'
                )";
            }, $searchWords)) . "
        )
        ORDER BY ord.co_orden DESC";

        $orderData = DB::select($query, [
            'co_usuario' => $userId
        ]);
        $data = collect($orderData);
        
        return $data;
    }

    public function search(OrderInstallationRequest $request)
    {
        
        $validated = $request->validate([
            'search' => 'required|string|min:1'
        ], [
            'search.required' => 'El campo de búsqueda es obligatorio.',
            'search.string' => 'El campo de búsqueda debe ser un texto.',
            'search.min' => 'El campo de búsqueda debe tener al menos 1 caracter.'
        ]);        
        
        $userId = Auth::id();
        $typeUser = $this->getUserType($userId);
        if($typeUser == 'user_system'){
            return back()
                ->with('error', 'No tiene permisos para acceder a Ordenes de Instalacion');
        }
        if($typeUser == 'administrative'){
            $ordenes = $this->getDataSearchAdministrative($request);
        }
        if($typeUser == 'office_manager' || $typeUser == 'installer'){
            $ordenes = $this->getDataSearchOfficeInstaller($request);
        }

        $status_ordenes = DB::select('SELECT * FROM i022t_estatus_orden order by co_estatus_orden');
        $perPage = 50; 
        $currentPage = $request->get('page', 1);
        $total = $ordenes->count();

        $paginatedData = new LengthAwarePaginator(
            $ordenes->forPage($currentPage, $perPage),
            $total,
            $perPage,
            $currentPage,
            ['path' => $request->url(), 'query' => $request->query()]
        );        
        
        return view('dashboard.ordenes-instalacion.index', compact('paginatedData', 'status_ordenes'));
       
    }
    
    public function exportOrders(OrderInstallationRequest $request)
    {
        $fechaInicio = $request->query('fechaInicio') ?? "";
        $fechaFin = $request->query('fechaFin') ?? "";        
        
        $userId = Auth::id();
        
        $typeUser = $this->getUserType($userId);
        
        if($typeUser == 'administrative'){
            $ordenes = $this->getDataAdministrative($request);
        }
        if($typeUser == 'office_manager' || $typeUser == 'installer'){
            $ordenes = $this->getDataOfficeInstaller($request);
        }
        
        $datos = $ordenes;
        
        $finalOrders = array();
        
        // Solo procesar si hay datos
        if(!empty($datos)) {
            foreach($datos as $item){
                $fecha_r = Carbon::parse($item->fecha_instalacion);
                $item->fecha_instalacion = $fecha_r->isoFormat('MM/DD/YYYY');
             
                $myOrders= array(
                    $item->co_orden.' '.$item->tx_primer_nombre_cliente.' '.$item->tx_primer_apellido_cliente,
                    $item->tx_telefono,                    
                    $item->accion,
                    $item->total_gastos_orden,
                    $item->estatus_de_la_orden,
                    $item->tx_primer_nombre_manager.' '.$item->tx_primer_apellido_manager,
                    $item->primer_nombre_plomero.' '.$item->primer_apellido_plomero,                    
                    $item->fecha_instalacion
                );        
                
                array_push($finalOrders,$myOrders);
            }
        } 
        
        $export = new OrderInstallationExport($finalOrders);
        $date = Carbon::now();
        $file = 'orders-'.$date->toDateTimeString().'.xlsx';
        return Excel::download($export, $file);         
    }

    
    protected function updateNotificationApp($co_usuario, $co_aplicacion) {
        // Check if there are any unread notifications for this user and application
        $hasUnread = DB::table('c036t_usuarios_notificacion_his')
            ->where('co_usuario', $co_usuario)
            ->where('co_aplicacion', $co_aplicacion)
            ->whereIn('co_tiponoti', [9,10])
            ->where('bo_visto', false)
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
                        AND co_tiponoti = :tipo_notificacion
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
                    'co_aplicacion' => $co_aplicacion,
                    'tipo_notificacion' => 9
                ]);

                // Segunda consulta: Actualizar c036t_usuarios_notificacion_his
                $sql2 = "
                    UPDATE c036t_usuarios_notificacion_his
                    SET bo_visto = TRUE
                    WHERE co_usuario = :co_usuario
                    AND co_aplicacion = :co_aplicacion
                    AND bo_visto = FALSE
                    AND co_tiponoti = :tipo_notificacion";

                $filasActualizadas2 = DB::update($sql2, [
                    'co_usuario' => $co_usuario,
                    'co_aplicacion' => $co_aplicacion,
                    'tipo_notificacion' => 9
                ]);

                return $filasActualizadas1 + $filasActualizadas2;
            });
            
            if ($resultado > 0) {                
                return true;
            } else {                
                return true;
            }


        } catch (\Exception $e) {
            Log::error('Error en la transacción para user ' . $co_usuario . ', app ' . $co_aplicacion . ': ' . $e->getMessage());
            return false;
        }
    }
  
public function getNotifications($co_usuario, $co_aplicacion, string $type = 'unread')
    {
        try {
            Log::info("Obteniendo notificaciones para usuario: {$co_usuario}, aplicación: {$co_aplicacion}, tipo: {$type}");
            
            $query = DB::table('c036t_usuarios_notificacion_his')
                ->where('co_usuario', $co_usuario)
                ->where('co_aplicacion', $co_aplicacion)
                // [RESOLUCIÓN] Se usa el 'whereIn' de la rama 'Plomero' para incluir ambos tipos
                ->whereIn('co_tiponoti', [9, 10]);
                
            // Se mantiene la lógica de la rama 'dev' para filtrar por no leídas
            if ($type === 'unread') {
                $query->where('bo_visto', false);
            }
            // Si es 'all', no se agrega filtro adicional (muestra todas)
            
            $notifications = $query
                ->orderBy('co_usrnotificahis', 'desc')
                ->orderBy('fe_registro', 'desc')
                ->get();
            
            Log::info("Encontradas {$notifications->count()} notificaciones");
            
            // Se mantiene el procesamiento de resultados de la rama 'dev'
            return $notifications->map(function ($notification) {
                try {
                    // Verificar si la fecha existe y es válida
                    if ($notification->fe_registro) {
                        $notification->fe_registro = Carbon::parse($notification->fe_registro);
                    } else {
                        $notification->fe_registro = Carbon::now();
                    }
                    $notification->highlight = false;
                    return $notification;
                } catch (\Exception $e) {
                    Log::error('Error al parsear fecha de notificación: ' . $e->getMessage());
                    $notification->fe_registro = Carbon::now();
                    $notification->highlight = false;
                    return $notification;
                }
            });
        } catch (\Exception $e) {
            Log::error('Error en getNotifications: ' . $e->getMessage());
            return collect([]); // Devuelve una colección vacía en caso de error
        }
    }
    public function showNotifications($co_aplicacion, $type = 'unread')
    {
        try {
            $co_usuario = auth()->id();
            
            if (!$co_usuario) {
                throw new \Exception('Usuario no autenticado');
            }
            
            if (!$co_aplicacion) {
                throw new \Exception('ID de aplicación no válido');
            }
            
            Log::info("showNotifications - Usuario: {$co_usuario}, Aplicación: {$co_aplicacion}, Tipo: {$type}");
            
            // Validar y normalizar el tipo
            $notificationType = ($type === 'all') ? 'all' : 'unread';
            
            // Obtener notificaciones directamente con una consulta más simple
            $query = DB::table('c036t_usuarios_notificacion_his')
                ->where('co_usuario', $co_usuario)
                ->where('co_aplicacion', $co_aplicacion)
                ->where('co_tiponoti', 9);
                
            if ($notificationType === 'unread') {
                $query->where('bo_visto', false);
            }
            
            $notifications = $query
                ->orderBy('co_usrnotificahis', 'desc')
                ->orderBy('fe_registro', 'desc')
                ->get();
            
            Log::info("Encontradas {$notifications->count()} notificaciones");
            
            // Procesar fechas de manera más segura
            $processedNotifications = $notifications->map(function ($notification) {
                $notification->fe_registro_formatted = 'Fecha no disponible';
                $notification->fe_registro_carbon = null;
                
                if ($notification->fe_registro) {
                    try {
                        $carbonDate = Carbon::parse($notification->fe_registro);
                        $notification->fe_registro_formatted = $carbonDate->diffForHumans();
                        $notification->fe_registro_carbon = $carbonDate;
                    } catch (\Exception $e) {
                        Log::warning("Error al parsear fecha: {$notification->fe_registro}");
                    }
                }
                
                return $notification;
            });
            
            Log::info("Renderizando vista con {$processedNotifications->count()} notificaciones procesadas");
            
            return view('dashboard.ordenes-instalacion.notify', [
                'notifications' => $processedNotifications,
                'co_aplicacion' => $co_aplicacion,
                'notification_type' => $notificationType 
            ])->render();
            
        } catch (\Exception $e) {
            Log::error('Error en showNotifications: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            
            return response()->json([
                'error' => 'Error interno del servidor',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function markNotificationsAsRead($co_aplicacion)
    {
        $co_usuario = auth()->id();
        
        try {
            $result = $this->updateNotificationApp($co_usuario, $co_aplicacion);
            
            if ($result) {
                return response()->json([
                    'success' => true,
                    'message' => 'Notificaciones marcadas como leídas correctamente'
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Error al marcar las notificaciones como leídas'
                ], 500);
            }
        } catch (\Exception $e) {
            Log::error('Error al marcar notificaciones como leídas: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error interno del servidor'
            ], 500);
        }
    }
    
    private function getManager(){
        $managers = C002tUsuario::whereIn('co_usuario', function($query) {
            $query->select('co_manager')
                  ->from('c038t_ordenes')
                  ->whereNotNull('co_manager')
                  ->distinct();
        })->get();
        return $managers;
    }
    /*Return installers for a manager*/
    public function getInstallers(){
        $installers = C002tUsuario::whereIn('co_usuario', function($query) {
            $query->select('co_plomero')
                  ->from('c038t_ordenes')
                  ->whereNotNull('co_plomero')
                  ->distinct();
        })->get();
        return $installers;
    }
    public function update(OrderInstallationUpdateRequest $request)
    {
        //$validated = $request->validated();
        
        

        try {
            $userId = Auth::id();
            $typeUser = $this->getUserType($userId);
            if(!$this->validateNewDateOrder($request->fe_instalacion)){
                //Esta respusta depende de la forma de llamarla
                return response()->json([
                    'success' => false,
                    'message' => 'La fecha de instalación no puede ser anterior a la fecha actual'
                ], 400);
            }
            
            $orden = C038tOrdenes::findOrFail($request->co_orden);
            //validateChangeStatusOrder($status_order_current,$status_order,$status_app,$typeUser)
            $result = $this->validateChangeStatusOrder($orden->co_estatus_orden, $request->co_estatus_orden, $orden->co_estatus_aplicacion, $typeUser);
            if(!$result['success']){
                //Esta respusta depende de la forma de llamarla
                return response()->json([
                    'success' => false,
                    'message' => $result['message']
                ], 400);
            }
            
            DB::beginTransaction();
            
            switch ($typeUser) {
                case 'administrative':
                            
                    //Update All data
                    $orden->update([
                        'co_plomero' => $request->co_plomero ?? $orden->co_plomero,
                        'co_estatus_orden' => $request->co_estatus_orden ?? $orden->co_estatus_orden,
                        'bo_accion' => $request->bo_accion ?? $orden->bo_accion,
                        'co_manager' => $request->co_manager ?? $orden->co_manager,                
                        'co_usuario_logueado' => $request->co_usuario_logueado ?? $orden->co_usuario_logueado,                
                    ]);        
                    $aplicacion = C001tAplicacione::find($orden->co_aplicacion);
                   
                    if ($request->co_estatus_orden == 2) {
                        if ($aplicacion) {
                            $aplicacion->update([
                                'co_estatus_aplicacion' => 366,
                                'fe_instalacion' => $request->fe_instalacion 
                            ]);
                        }
                    }else{
                        if ($aplicacion) {
                            $aplicacion->update([
                                'fe_instalacion' => $request->fe_instalacion 
                            ]);
                        }
                    }
        
                    // 3. Actualizar gastos asociados
                    if ($request->has('gastos')) {
                        $this->updateBills($request->co_orden, $request->gastos);
                    }
                    
                    break;
                case 'office_manager':
                    //Update All data excepto plomero and manager
                    $orden->update([
                        'co_estatus_orden' => $request->co_estatus_orden ?? $orden->co_estatus_orden,
                        'bo_accion' => $request->bo_accion ?? $orden->bo_accion,
                        'co_usuario_logueado' => $request->co_usuario_logueado ?? $orden->co_usuario_logueado,                
                    ]);        
                    
                    $aplicacion = C001tAplicacione::find($orden->co_aplicacion);
                   
                    if ($request->co_estatus_orden == 2) {
                        if ($aplicacion) {
                            $aplicacion->update([
                                'co_estatus_aplicacion' => 366,
                                'fe_instalacion' => $request->fe_instalacion 
                            ]);
                        }
                    }else{
                        if ($aplicacion) {
                            $aplicacion->update([
                                'fe_instalacion' => $request->fe_instalacion 
                            ]);
                        }
                    }
        
                    // 3. Actualizar gastos asociados
                    if ($request->has('gastos')) {
                        $this->updateBills($request->co_orden, $request->gastos);
                    }
                    break;
                case 'installer':
                    //Update status order
                    $orden->update([
                        'co_estatus_orden' => $request->co_estatus_orden ?? $orden->co_estatus_orden,                        
                    ]);        
                    // update costs 
                    if ($request->has('gastos')) {
                        $this->updateBills($request->co_orden, $request->gastos);
                    }
                    break;                    
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Orden actualizada correctamente',
                'data' => [
                    'orden' => $orden->fresh(),
                    'gastos' => $orden->gastos()->get()
                ]
            ]);

        } catch (\Exception $e) {
            DB::rollback();
            
            return response()->json([
                //'success' => false,
                'message' => $e->getMessage(),
                'error' => true
            ], 400);
        }      
        
    }
    private function updateBills($co_orden, $bills)
    {
        // Eliminar gastos existentes
        C039tOrdenGasto::where('co_orden', $co_orden)->delete();

        // Insertar nuevos gastos
        foreach ($bills as $item) {
            C039tOrdenGasto::create([
                'co_orden' => $co_orden,
                'co_tipo_gasto_inst' => $item['co_tipo_gasto_inst'],
                'nu_monto_gasto' => $item['nu_monto_gasto'],
            ]);
        }
    }
    private function validateNewDateOrder($installation_date){
        $installation_date = Carbon::parse($installation_date)->format('Y-m-d');
        $current_date = Carbon::now()->format('Y-m-d');
        if($installation_date < $current_date){
            return false;
        }
        return true;
    }
    private function validateChangeStatusOrder($status_order_current,$status_order,$status_app,$typeUser){
        $result = array(
            'success' => false,             
            'message' => ''
        );
        //order status install and app is canceled or paused
        if($status_order !=3 && ($status_app == 372 || $status_app == 378)){
            $result['success'] = false;
            $result['message'] = 'La orden no puede ser actualizada porque la aplicación esta cancelada o pausada';
            return $result;
        }
        //order status pending or install and order status actual is canceled
        if($status_order != 3 && $status_order_current == 3){
            $result['success'] = false;
            $result['message'] = 'La orden no puede ser actualizada porque esta cancelada';
            return $result;
        }

        if($typeUser == 'user_system'){
            $result['success'] = false;
            $result['message'] = 'No tienes permisos para actualizar la orden';
            return $result;
        }

        if($typeUser == 'administrative' || $typeUser == 'office_manager'){
            $result['success'] = true;            
        }elseif($typeUser == 'installer'){
            if($status_order == 1 || $status_order == 2){
                $result['success'] = true;
            }else{ //order status is canceled
                $result['success'] = false;
                $result['message'] = 'No tienes permisos para canelar la orden';
            }
        }
        return $result;
    }
}