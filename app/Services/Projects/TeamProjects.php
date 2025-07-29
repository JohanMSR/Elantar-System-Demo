<?php

namespace App\Services\Projects;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Illuminate\Support\Number;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Cache;

class TeamProjects extends Model implements Projects
{
    use HasFactory;
    protected $data;
    protected $total;

    public function __construct()
    {
        $this->total = 0;
    }
    
    public function getAnalyst()
    {
        // Usar caché para evitar consultas repetidas
        $co_usuario_logueado = Auth()->id();
        $cacheKey = "analistas_equipo_{$co_usuario_logueado}";
        
        if (Cache::has($cacheKey)) {
            return Cache::get($cacheKey);
        }
        
        $sql = "WITH RECURSIVE equipo(co_usuario, analista) AS (
            SELECT usr1.co_usuario, usr1.tx_primer_nombre || ' ' || usr1.tx_primer_apellido as analista
            FROM public.c002t_usuarios as usr1 WHERE usr1.co_usuario = $co_usuario_logueado
            UNION ALL
            SELECT c.co_usuario, c.tx_primer_nombre || ' ' || c.tx_primer_apellido as analista
            FROM public.c002t_usuarios c INNER JOIN equipo ct ON ct.co_usuario = c.co_usuario_padre) 
            select * from equipo order by equipo.analista ASC";

        $data = DB::select($sql);
        
        // Guardar en caché por un tiempo razonable (1 hora)
        Cache::put($cacheKey, $data, 60 * 60);
        
        return $data;   
    }


    public function getOrder(Request $request)
    {
        //ORDER BY ap1.co_aplicacion DESC
        $order = $request->order ?? "1";

        if($order == "1"){
            //$order = " ORDER BY proyectos.co_qb_id_proyecto DESC";
            $order = " ORDER BY ap1.co_aplicacion DESC";
        }
        if($order == "2"){
            //$order = " ORDER BY proyectos.fe_creacion DESC"; 
            $order = " ORDER BY ap1.fe_creacion DESC";
        }
        if($order == "3"){
            //$order = " ORDER BY proyectos.tx_ciudad ASC";
            $order = " ORDER BY cl1.tx_ciudad ASC";
        }
    
        if($order == "4"){
          //  $order = " ORDER BY proyectos.tx_estado ASC";
            $order = " ORDER BY cl1.tx_estado ASC";
        }
        return $order;

    }

    public function getProjects(Request $request)
    {
        $co_usuario_logueado = Auth()->id();

        $bandF = 0;
        $startDate = $request->startDate ?? "";
        $endDate = $request->endDate ?? "";
        $statusApp = $request->statusApp ?? "";
        $analyst = $request->analyst ?? "";
        $project_id = $request->project_id ?? "";
        $client_name = $request->client_name ?? "";
        
        $sql = "WITH RECURSIVE equipo(co_usuario, analista) AS (
            SELECT  usr1.co_usuario, usr1.tx_primer_nombre || ' '  || usr1.tx_primer_apellido as analista
            FROM public.c002t_usuarios as usr1 WHERE usr1.co_usuario = $co_usuario_logueado
            UNION ALL
            SELECT  c.co_usuario, c.tx_primer_nombre  || ' ' 	||  c.tx_primer_apellido as analista
            FROM public.c002t_usuarios c INNER JOIN equipo ct ON ct.co_usuario = c.co_usuario_padre 
			), 
	        ultimoEdo AS (
		    SELECT 
            co_aplicacion, 
            MAX(fe_activacion_estatus) AS ultima_fecha
            FROM 
                c026t_aplicaciones_estatus_historial as eh1  
            GROUP BY 
                co_aplicacion
            ),
            notificaciones_no_leidas AS (
                SELECT 
                    co_aplicacion,
                    COUNT(*) as total_no_leidas
                FROM 
                    c036t_usuarios_notificacion_his
                WHERE 
                    co_usuario = '$co_usuario_logueado'
                    AND bo_visto = false
                GROUP BY 
                    co_aplicacion
            )
            SELECT 
            ap1.co_aplicacion, 
            cl1.co_cliente,
            cl1.tx_primer_nombre, 
            cl1.tx_primer_apellido,
            cl1.tx_direccion1, 
            cl1.tx_direccion2,
            cl1.tx_ciudad,
            cl1.tx_estado,
            cl1.tx_zip,
            cl1.tx_email,
            cl1.tx_telefono,
            ap1.fe_creacion,
            us1.co_usuario as co_usuario_owner,
            us1.tx_primer_nombre || ' ' || us1.tx_primer_apellido as OwnerName, 
            us2.co_usuario as co_usuario_setter,
            us2.tx_primer_nombre || ' ' || us2.tx_primer_apellido as SetterName, 
            cl1.tx_estado, 
            cl1.tx_ciudad,
            es1.tx_nombre     as estatus_mas_reciente,
            eh1.ultima_fecha  as fe_activacion_estatus_mas_reciente, 
            es2.tx_nombre     as estatus_app_siguiente,
            ap1.nu_precio_total, 
            ap1.co_estatus_aplicacion,
            COALESCE(nnl.total_no_leidas, 0) as notificaciones
        FROM c001t_aplicaciones as ap1 
        INNER JOIN c003t_clientes as cl1 ON ap1.co_cliente = cl1.co_cliente 
        LEFT  JOIN c002t_usuarios as us1 ON us1.co_usuario = ap1.co_usuario 
        LEFT  JOIN c002t_usuarios as us2 ON us2.co_usuario = ap1.co_usuario_2 
        INNER JOIN i001t_estatus_aplicaciones as es1 ON es1.co_estatus_aplicacion = ap1.co_estatus_aplicacion 
        INNER JOIN i001t_estatus_aplicaciones as es2 ON es2.co_estatus_aplicacion = es1.co_estatus_siguiente 
        INNER JOIN ultimoEdo as eh1 ON eh1.co_aplicacion = ap1.co_aplicacion 
        INNER JOIN equipo as eq1 ON eq1.co_usuario = ap1.co_usuario 
        LEFT JOIN notificaciones_no_leidas as nnl ON nnl.co_aplicacion = ap1.co_aplicacion
        WHERE ap1.co_usuario IN (Select co_usuario from equipo)";
        
        if($startDate!="" && $endDate!=""){
            $fecha1 = Carbon::parse($startDate)->isoFormat('Y-M-D');
            $fecha2 = Carbon::parse($endDate)->isoFormat('Y-M-D');
            $bandF = 1;
        }    

        if($bandF == 1){
           $sql = $sql . " AND (ap1.fe_creacion BETWEEN DATE('$fecha1') and DATE('$fecha2'))";
        }
        
        if(strcasecmp($project_id,"")!=0){
            $sql = $sql . " AND ap1.co_aplicacion = '$project_id'";
        }
        
        if(strcasecmp($statusApp,"")!=0){
            $sql = $sql . " AND ap1.co_estatus_aplicacion = $statusApp";
        }
        
        if(strcasecmp($analyst,"")!=0){
            $sql = $sql . "AND (ap1.co_usuario = $analyst or ap1.co_usuario_2 = $analyst)";
        }
        
        if(strcasecmp($client_name,"")!=0){
            
            $searchWords = explode(' ', $client_name);
            
            $searchCondition = implode(' OR ', array_map(function($word) {
            return "(
                    unaccent(LOWER(cl1.tx_primer_nombre)) LIKE unaccent(LOWER('%$word%')) OR
                    unaccent(LOWER(cl1.tx_primer_apellido)) LIKE unaccent(LOWER('%$word%')) OR
                    unaccent(LOWER(cl1.tx_direccion1)) LIKE unaccent(LOWER('%$word%')) OR
                    unaccent(LOWER(cl1.tx_direccion2)) LIKE unaccent(LOWER('%$word%')) OR 
                    unaccent(LOWER(cl1.tx_email)) LIKE unaccent(LOWER('%$word%'))
                )";
            }, $searchWords));

            $sql = $sql . " AND ($searchCondition)";
        }
        
       $order = $this->getOrder($request);     
       $sql = $sql . $order;
       $data = DB::select($sql);
       
       if(count($data) > 0){
            foreach($data as $item){                
                $this->total += $item->nu_precio_total;             
            }
        }           
       
        return $data;  
    }

    public function getProjectsWithPagination(Request $request, int $perPage = 10)
    {
        // Obtener todos los proyectos una sola vez
        $allProjects = $this->getProjects($request);
    
        // Paginar manualmente los resultados
        $page = $request->input('page', 1);
        $offset = ($page - 1) * $perPage;
        // Crear la colección paginada
        $items = array_slice($allProjects, $offset, $perPage);
        $paginator = new \Illuminate\Pagination\LengthAwarePaginator(
            $items,
            count($allProjects),
            $perPage,
            $page,
            ['path' => \Illuminate\Support\Facades\Request::url()]
        );
    
        return [
            'all' => $allProjects,
            'paginated' => $paginator
        ];
    }

    public function getConsulta(Request $request)
    {
        $co_usuario_logueado = Auth()->id();

        $bandF = 0;
        $startDate = $request->startDate ?? "";
        $endDate = $request->endDate ?? "";
        $statusApp = $request->statusApp ?? "";
        $analyst = $request->analyst ?? "";
        $project_id = $request->project_id ?? "";
        $client_name = $request->client_name ?? "";
        
        $sql = "WITH RECURSIVE equipo(co_usuario, analista) AS (
            SELECT  usr1.co_usuario, usr1.tx_primer_nombre || ' '  || usr1.tx_primer_apellido as analista
            FROM public.c002t_usuarios as usr1 WHERE usr1.co_usuario = $co_usuario_logueado
            UNION ALL
            SELECT  c.co_usuario, c.tx_primer_nombre  || ' ' 	||  c.tx_primer_apellido as analista
            FROM public.c002t_usuarios c INNER JOIN equipo ct ON ct.co_usuario = c.co_usuario_padre 
			), 
	ultimoEdo AS (
		SELECT 
        co_aplicacion, 
        MAX(fe_activacion_estatus) AS ultima_fecha
    FROM 
        c026t_aplicaciones_estatus_historial as eh1  
    GROUP BY 
        co_aplicacion
	)
SELECT 
	ap1.co_aplicacion, 
	cl1.tx_primer_nombre, 
	cl1.tx_primer_apellido,
	ap1.fe_creacion,
    us1.co_usuario as co_usuario_owner,
	us1.tx_primer_nombre || ' ' || us1.tx_primer_apellido as OwnerName, 
    us2.co_usuario as co_usuario_setter,
	us2.tx_primer_nombre || ' ' || us2.tx_primer_apellido as SetterName, 
	cl1.tx_estado, 
	cl1.tx_ciudad,
	es1.tx_nombre     as estatus_mas_reciente,
	eh1.ultima_fecha  as fe_activacion_estatus_mas_reciente, 
	es2.tx_nombre     as estatus_app_siguiente,
	ap1.nu_precio_total, 
	ap1.co_estatus_aplicacion 
 FROM c001t_aplicaciones as ap1 
  INNER JOIN c003t_clientes as cl1 ON ap1.co_cliente = cl1.co_cliente 
	LEFT  JOIN c002t_usuarios as us1 ON us1.co_usuario = ap1.co_usuario 
	LEFT  JOIN c002t_usuarios as us2 ON us2.co_usuario = ap1.co_usuario_2 
	INNER JOIN i001t_estatus_aplicaciones as es1 ON es1.co_estatus_aplicacion = ap1.co_estatus_aplicacion 
	INNER JOIN i001t_estatus_aplicaciones as es2 ON es2.co_estatus_aplicacion = es1.co_estatus_siguiente 
	INNER JOIN ultimoEdo as eh1 ON eh1.co_aplicacion = ap1.co_aplicacion 
	INNER JOIN equipo as eq1 ON eq1.co_usuario = ap1.co_usuario 
	WHERE ap1.co_usuario IN (Select co_usuario from equipo)
	";
    //AND (ap1.fe_creacion BETWEEN DATE('$fecha1') and DATE('$fecha2'))
    //ORDER BY ap1.co_aplicacion DESC

        if($startDate!="" && $endDate!=""){
            $fecha1 = Carbon::parse($startDate)->isoFormat('Y-M-D');
            $fecha2 = Carbon::parse($endDate)->isoFormat('Y-M-D');
            $bandF = 1;
        }    

        if($bandF == 1){
           // $sql = $sql . " WHERE (proyectos.fe_creacion BETWEEN DATE('$fecha1') and DATE('$fecha2'))";
           $sql = $sql . " AND (ap1.fe_creacion BETWEEN DATE('$fecha1') and DATE('$fecha2'))";
        }

        if((strcasecmp($project_id,"")!=0) && $bandF == 1){
            $sql = $sql . " AND ap1.co_aplicacion = '$project_id'";
        }elseif((strcasecmp($project_id,"")!=0) && $bandF == 0){
            $sql = $sql . " WHERE ap1.co_aplicacion = '$project_id'";
            $bandF = 1;
        }

        if((strcasecmp($statusApp,"")!=0) && $bandF == 1){
            $sql = $sql . " AND ap1.co_estatus_aplicacion = $statusApp";
        }elseif((strcasecmp($statusApp,"")!=0) && $bandF == 0){
            $sql = $sql . " WHERE ap1.co_estatus_aplicacion = $statusApp";
            $bandF = 1;
        }          

        if((strcasecmp($analyst,"")!=0) && $bandF == 1){
            //and c001t_aplicaciones.co_estatus_aplicacion = $codigo_estatus_aplicacion
            $sql = $sql . "AND (ap1.co_usuario = $analyst or ap1.co_usuario_2 = $analyst)";
        }elseif((strcasecmp($analyst,"")!=0) && $bandF == 0){
            $sql = $sql . " WHERE (ap1.co_usuario = $analyst or ap1.co_usuario_2 = $analyst)";
        }          

        if((strcasecmp($client_name,"")!=0) && $bandF == 1){
            // Dividir el nombre completo en partes
            $nameParts = explode(' ', trim($client_name));
            $nameConditions = [];
            
            // Agregar condición para el nombre completo exacto
            $nameConditions[] = "LOWER(CONCAT(cl1.tx_primer_nombre, ' ', cl1.tx_primer_apellido)) LIKE LOWER('%$client_name%')";
            
            // Si hay dos o más partes, buscar la combinación exacta de nombre y apellido
            if(count($nameParts) >= 2) {
                $firstName = $nameParts[0];
                $lastName = end($nameParts);
                $nameConditions[] = "(LOWER(cl1.tx_primer_nombre) LIKE LOWER('%$firstName%') AND LOWER(cl1.tx_primer_apellido) LIKE LOWER('%$lastName%'))";
            }
            
            $sql = $sql . " AND (" . implode(' OR ', $nameConditions) . ")";
        }elseif((strcasecmp($client_name,"")!=0) && $bandF == 0){
            $sql = $sql . " WHERE (LOWER(ap1.tx_primer_nombre) LIKE LOWER('%$client_name%') OR LOWER(ap1.tx_primer_apellido) LIKE LOWER('%$client_name%'))";
            $bandF = 1;
        }
        
       $order = $this->getOrder($request);     
       $sql = $sql . $order;
        
    
        return $sql;
    }

    public function getTotalize()
    {
        return $this->total;
    }


}
