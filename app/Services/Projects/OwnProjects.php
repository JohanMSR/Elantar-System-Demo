<?php

namespace App\Services\Projects;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Illuminate\Support\Number;
use Illuminate\Support\Facades\DB;


class OwnProjects extends Model implements Projects
{
    use HasFactory;
    protected $total;

    public function __construct()
    {
        $this->total = 0;
    }

    public function getAnalyst()
    {
        $co_usuario_logueado = Auth()->id();
        $sql = "WITH analistas (co_usuario, analista, fe_creacion) AS (
        SELECT appl1.co_usuario as co_analista, U1.tx_primer_nombre || ' '  || U1.tx_primer_apellido as analista, appl1.fe_creacion
        FROM c001t_aplicaciones as appl1 inner JOIN  c002t_usuarios U1 ON (U1.co_usuario = appl1.co_usuario)
        WHERE (appl1.co_usuario   = $co_usuario_logueado OR appl1.co_usuario_2 = $co_usuario_logueado)
        UNION ALL 
        SELECT appl2.co_usuario_2 as co_analista, U2.tx_primer_nombre || ' '  || U2.tx_primer_apellido as analista, appl2.fe_creacion
        FROM c001t_aplicaciones as appl2 inner JOIN  c002t_usuarios U2 ON (U2.co_usuario = appl2.co_usuario_2)
        WHERE (appl2.co_usuario   = $co_usuario_logueado OR appl2.co_usuario_2   = $co_usuario_logueado)
        ) SELECT DISTINCT co_usuario, analista FROM  analistas 
        ORDER BY analistas.analista";

        $data = DB::select($sql);
        return $data;
    }

    public function getOrder(Request $request)
    {
        $order = $request->order ?? "1";

        if($order == "1"){
            $order = " ORDER BY ap1.co_aplicacion DESC";
        }
        if($order == "2"){
            $order = " ORDER BY ap1.fe_creacion DESC"; 
        }
        if($order == "3"){
            $order = " ORDER BY cl1.tx_ciudad ASC";
        }
    
        if($order == "4"){
            $order = " ORDER BY cl1.tx_estado ASC";
        }
        return $order;

    }

    public function getProjects(Request $request)
    {
    
        $co_usuario_logueado = Auth()->id();

        $startDate = $request->startDate ?? "";
        $endDate = $request->endDate ?? "";
        $statusApp = $request->statusApp ?? "";
        $analyst = $request->analyst ?? "";
        $project_id = $request->project_id ?? "";
        $client_name = $request->client_name ?? "";
        
        
        $sql = "WITH ultimoEdo AS (
		    SELECT co_aplicacion, MAX(fe_activacion_estatus) AS ultima_fecha
            FROM  c026t_aplicaciones_estatus_historial as eh1  
            GROUP BY co_aplicacion
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
            LEFT JOIN notificaciones_no_leidas as nnl ON nnl.co_aplicacion = ap1.co_aplicacion
            WHERE   ((ap1.co_usuario   = $co_usuario_logueado)  
            OR  (ap1.co_usuario_2 = $co_usuario_logueado))
            ";

        if($startDate!="" && $endDate!=""){
            $fecha1 = Carbon::parse($startDate)->isoFormat('Y-M-D');
            $fecha2 = Carbon::parse($endDate)->isoFormat('Y-M-D');
            $sql = $sql . " AND (ap1.fe_creacion BETWEEN  DATE('$fecha1') AND DATE('$fecha2'))";
        }    

        if((strcasecmp($project_id,"")!=0)){
            $sql = $sql . " AND ap1.co_aplicacion = '$project_id'";
        }

        if((strcasecmp($statusApp,"")!=0)){
            $sql = $sql . " AND ap1.co_estatus_aplicacion = $statusApp";
        }   

        if((strcasecmp($client_name,"")!=0)){
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

        $startDate = $request->startDate ?? "";
        $endDate = $request->endDate ?? "";
        $statusApp = $request->statusApp ?? "";
        $analyst = $request->analyst ?? "";
        $project_id = $request->project_id ?? "";
        $client_name = $request->client_name ?? "";
        
        
        $sql = "WITH ultimoEdo AS (
		    SELECT co_aplicacion, MAX(fe_activacion_estatus) AS ultima_fecha
            FROM  c026t_aplicaciones_estatus_historial as eh1  
            GROUP BY co_aplicacion
            ),
            notificaciones_no_leidas AS (
                SELECT 
                    co_aplicacion,
                    COUNT(*) as total_no_leidas
                FROM 
                    c036t_usuarios_notificacion_his
                WHERE 
                    co_usuario = $co_usuario_logueado
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
            LEFT JOIN notificaciones_no_leidas as nnl ON nnl.co_aplicacion = ap1.co_aplicacion
            WHERE   ((ap1.co_usuario   = $co_usuario_logueado)  
            OR  (ap1.co_usuario_2 = $co_usuario_logueado))
            ";

        if($startDate!="" && $endDate!=""){
            $fecha1 = Carbon::parse($startDate)->isoFormat('Y-M-D');
            $fecha2 = Carbon::parse($endDate)->isoFormat('Y-M-D');
            $sql = $sql . " AND (ap1.fe_creacion BETWEEN  DATE('$fecha1') AND DATE('$fecha2'))";
        }    

        if((strcasecmp($project_id,"")!=0)){
            $sql = $sql . " AND ap1.co_aplicacion = '$project_id'";
        }

        if((strcasecmp($statusApp,"")!=0)){
            $sql = $sql . " AND ap1.co_estatus_aplicacion = $statusApp";
        }   

        if((strcasecmp($client_name,"")!=0)){
            $sql = $sql . " AND (LOWER(cl1.tx_primer_nombre) LIKE LOWER('%$client_name%') OR LOWER(cl1.tx_primer_apellido) LIKE LOWER('%$client_name%'))";
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