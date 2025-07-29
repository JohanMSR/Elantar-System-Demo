<?php

namespace App\Http\Controllers;

use App\Exports\ProjectsExport;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rules;
use App\Models\C003tCliente;
use App\Models\I001tEstatusAplicacione;
use App\Models\I004tZipcode;
use Illuminate\Http\RedirectResponse;


use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Number;
use App\Services\Projects\ProjectFactory;
use Illuminate\Support\Arr;
use stdClass;
use App\Models\DateHelper;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
class AccountController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(protected ProjectFactory $projectFactory)
    {
        $this->middleware('auth');
        $this->middleware('IsActive');
    }

    public function index(Request $request, string $type='teamprojects')
    {
       
        $start_time = Carbon::now();
        $co_usuario_logueado = Auth()->id();
        
        // Obtener perPage y proyectos
        $perPage = $request->input('perPage', 10);
        $projects = $this->projectFactory::create($type);
        $data = $projects->getProjectsWithPagination($request, $perPage);
        $allProjects = $data['all'];
        $paginated = $data['paginated'];
        
        $total = $projects->getTotalize();
        
        // Variables para analistas y status
        $select_statusApp = [];
        
        if(count($allProjects) > 0) {
            $analyst = $request->input('select_analyst', "");
            $finalAnalyst = $this->filterAndSelectAnalysts($allProjects, $analyst);
            
            // Procesar status
            $select_statusApp = $this->filterStatusApp($allProjects);
            if(count($select_statusApp) <= 0) {
                Session::flash('error_f', 'No se encontraron Estatus para las aplicaciones.');
            }
        } else {
            $finalAnalyst = [];
            Session::flash('error_f', 'No se encontraron Proyectos para listar.');
        }
        
        Log::info('7. Tiempo filtrado analistas optimizado: ' . now()->diffInMilliseconds($start_time));
        
        return view('dashboard.account.account')
            ->with('proyectos', $paginated)
            ->with('select_order', "")
            ->with('date1', "")
            ->with('date2', "")
            ->with('type', $type)
            ->with('select_analyst', $finalAnalyst)
            ->with('select_statusApp', $select_statusApp)
            ->with('co_usuario_logueado', $co_usuario_logueado)
            ->with('perPage', $perPage)
            ->with('total', $total)
            ->with('open_chat', $request->query('open_chat'));    
    }

    public function searchProjects(Request $request,string $type='teamprojects')
    {
        $co_usuario_logueado = Auth()->id();
        $projects = $this->projectFactory::create($type);
        
        // Solo validar fechas si ambas están presentes
        if (!empty($request->query('startDate')) && !empty($request->query('endDate'))) {
            $validateStartDate = DateHelper::validarFecha($request->query('startDate'));
            $validateEndDate = DateHelper::validarFecha($request->query('endDate'));
            
            if($validateStartDate != true  || $validateEndDate != true){
                Session::flash('error_f', 'Debe ingresar un rango de fecha válido.');
                return back();
            }
        }

        // Validar que project_id sea un entero si está presente
        if ($request->has('project_id') && $request->project_id != '') {
            $validator = Validator::make($request->all(), [
            'project_id' => 'integer',
        ], [
            'project_id.integer' => 'El ID del proyecto debe ser un número entero',
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()
                    ->withErrors($validator)
                    ->withInput()
                    ->with('error', 'El ID del proyecto debe ser un número entero');
            }
        }

        $filteredRequest = new Request([
            'startDate' => $request->query('startDate'),
            'endDate' =>  $request->query('endDate'),
            'order' => $request->query('select_order'),
            'project_id' => $request->query('project_id'),
            'client_name' => $request->query('client_name'),
            'statusApp' => $request->query('select_statusApp'),
            'analyst' => $request->query('select_analyst'),
            'page' => $request->query('page', 1),
            'perPage' => $request->query('perPage', 10),
        ]);
        
        $perPage = $request->query('perPage', 10);
        $data = $projects->getProjectsWithPagination($filteredRequest, $perPage);
        $allData = $data['all'];
        $paginatedData = $data['paginated'];
        
        $total = $projects->getTotalize();
        
        // Initialize empty arrays for analyst and status
        $finalAnalyst = [];
        $select_statusApp = [];
        
        if(count($allData) > 0) {
            $analyst = $request->query('select_analyst', "");
           
            $finalAnalyst = $this->filterAndSelectAnalysts($allData, $analyst);
           
            $select_statusApp = $this->filterStatusApp($allData);
        } else {
                Session::flash('error_f', 'No existen proyectos para los criterios indicados.');
                return back();
        }
        
        return view('dashboard.account.account')
        ->with('proyectos', $paginatedData)
        ->with('select_order' ,$request->input('select_order'))
        ->with('date1' ,$request->input('startDate'))
        ->with('date2' ,$request->input('endDate'))
        ->with('project_id', $request->input('project_id'))
        ->with('client_name', $request->input('client_name'))
        ->with('type' ,$type)
        ->with('select_analyst', $finalAnalyst)
        ->with('select_statusApp' ,$select_statusApp)
        ->with('co_usuario_logueado' ,$co_usuario_logueado)
        ->with('perPage', $perPage)
        ->with('total',$total)
        ->with('open_chat', $request->query('open_chat'));
    }

    public function armarRequestProjects(Request $request){

        $co_cliente = $request->input('co_cliente');
        $co_aplicacion = $request->input('co_aplicacion');
        $co_usuario = Auth()->id();
        
        $sql = '
            SELECT 
            c003t_clientes.tx_direccion1 AS address_address,
            c003t_clientes.tx_direccion2 AS address_address2,
            c003t_clientes.tx_ciudad AS address_city,
            i010t_estados.tx_siglas AS address_state,
            i004t_zipcodes.tx_zip AS address_zip,
            c003t_clientes.tx_primer_nombre AS  first_name,
            c003t_clientes.tx_segundo_nombre AS  middle_initial,
            c003t_clientes.tx_primer_apellido AS last_name,
            c003t_clientes.tx_email AS  email,
            c003t_clientes.tx_telefono AS  phone,
            c002t_usuarios.co_ryve_usuario AS  ownerid,
            c002t_usuarios.co_quick_base_id,
            c002t_usuarios.co_office_manager,
            c002t_usuarios.co_sponsor,
            
            c003t_clientes.co_quick_base_id,
            c003t_clientes.tx_url_proposals,
            c003t_clientes.co_qb_setter,
            c003t_clientes.co_qb_owner,
            c003t_clientes.co_cliente,

            c003t_clientes.co_ryve_owner,
            c003t_clientes.co_ryve_setter

        FROM 
            c003t_clientes 
        left JOIN 
            i010t_estados ON i010t_estados.co_estado = c003t_clientes.co_estado
        left JOIN 
            c002t_usuarios ON c002t_usuarios.co_usuario = c003t_clientes.co_usuario
        left JOIN 
            i004t_zipcodes ON i004t_zipcodes.co_zip = c003t_clientes.co_zip
        WHERE 
            c003t_clientes.co_cliente = ' . $co_cliente; 

        $datos = DB::select($sql);
        
        if(count($datos)>0){
            
            $url = "?";
            $url .= "ownerID=".$datos[0]->co_ryve_owner;
            $url .= "&address-address=".$datos[0]->address_address;
            $url .= "&address-address2=".$datos[0]->address_address2;
            $url .= "&address-city=".$datos[0]->address_city;
            $url .= "&address-state=".$datos[0]->address_state;
            $url .= "&address-zip=".$datos[0]->address_zip;
            $url .= "&First+Name=".$datos[0]->first_name;
            $url .= "&Middle+Initial=".$datos[0]->middle_initial;
            $url .= "&Last+Name=".$datos[0]->last_name;
            $url .= "&Email=".$datos[0]->email;
            $url .= "&Phone=".$datos[0]->phone;

            $url .= "&AccountID=".$datos[0]->co_cliente;
            $url .= "&Setter=".$datos[0]->co_ryve_setter;
            
            $aux1 = $datos[0]->tx_url_proposals;//env('PRECUALIFICACION').$url;
            $aux2 = env('PAYMENTAUTORIZACION').$url;
            $aux3 = env('SITEPHOTOS').$url;
            $aux4 = env('VERIFICATIONDOCUMENTS').$url;
            $aux5 = env('REFERRED').$url;
            
            $base_url = [$aux1, $aux2, $aux3, $aux4, $aux5];

            $notifyUpdate = $this->updateNotificationApp($co_usuario, $co_aplicacion);
            if(!$notifyUpdate){
                return Response::json(array('error'=>false,'msg'=>'No se pudo actualizar las notificaciones.'), 422);
            }
            return Response::json(array('success'=>true,'msg'=>'Forma construida.', 'datos' => $base_url), 200); 
       
        }else{

            return Response::json(array('error'=>false,'msg'=>'No se pudo armar las solicitudes para este Cliente.'), 422);
        }

    }

    public function orderListBtn(Request $request){
        
        $data = [];
        $consulta = [];
        $msg = 'No se encontraron Proyectos para los criterios indicados.';
        $type = $request->type;
        $projects = $this->projectFactory::create($type);
        
        $startDate = $request->query('startDate') ?? "";
        $endDate = $request->query('endDate') ?? "";
        $project_id = $request->query('project_id') ?? "";
        $client_id = $request->query('client_id') ?? "";
        $perPage = $request->query('perPage', 10);
        
        // Solo validar fechas si ambas están presentes
        if (!empty($startDate) && !empty($endDate)) {
            $validateStartDate = DateHelper::validarFecha($request->startDate);
            $validateEndDate = DateHelper::validarFecha($request->endDate);
            
            if (!$validateStartDate || !$validateEndDate) {
                return Response::json(array('error'=>false,'msg'=>'Debe ingresar un rango de fecha válido', 'consulta'=> $consulta), 422);
            }
        }
        
        // Get both paginated and all data        
        
        $data = $projects->getProjectsWithPagination($request, $perPage);
        $allData = $data['all'];
        $paginatedData = $data['paginated'];
        
        $total = $projects->getTotalize();
        
        if(count($allData) > 0){   
            
            $analyst = $request->query('analyst');
        
            $finalAnalyst = $this->filterAndSelectAnalysts($allData, $analyst);
        
            $statusApp = $request->query('statusApp');
            $select_statusApp = $this->filterStatusApp($allData, $statusApp);
            return Response::json(array(
               'success'=>true,
               'msg'=>'Proyectos cargados orden# '. $request->order, 
               'data' => $paginatedData->items(),
               'pagination' => [
                   'total' => $paginatedData->total(),
                   'per_page' => $paginatedData->perPage(),
                   'current_page' => $paginatedData->currentPage(),
                   'last_page' => $paginatedData->lastPage(),
                   'from' => $paginatedData->firstItem(),
                   'to' => $paginatedData->lastItem(),
                   'links' => $paginatedData->linkCollection()->toArray()
               ],               
               'select_statusApp'=> $select_statusApp,
               'select_analyst'=> $finalAnalyst,
               'statusApp' => $statusApp,
               'total' => $total
           ), 200); 
        } else {
           return Response::json(array('error'=>false,'msg'=>$msg), 422);
        }
    }

   
    public function exportProjects(Request $request, string $type='teamprojects')
    {
        $startDate = $request->query('startDate') ?? "";
        $endDate = $request->query('endDate') ?? "";
        
        if(!empty($startDate) && !empty($endDate))
        {
            $validateStartDate = DateHelper::validarFecha($request->query('startDate'));
            $validateEndDate = DateHelper::validarFecha($request->query('endDate'));
        
            if($validateStartDate != true  || $validateEndDate != true)
            {
                Session::flash('error_f', 'Debe ingresar un rango de fecha válido.');
                return redirect()->route('account',['type'=>$type]);
            }
        }
        
        $filteredRequest = new Request([
            'startDate' => $request->query('startDate'),
            'endDate' =>  $request->query('endDate'),
            'order' => $request->query('select_order'),
            'statusApp' => $request->query('select_statusApp'),
            'analyst' => $request->query('select_analyst'),
            'project_id' => $request->query('project_id'),
            'client_name' => $request->query('client_name')
        ]);
        
        $projects = $this->projectFactory::create($type);
        $datos = $projects->getProjects($filteredRequest);
        
        $finalProjects = array();
        
        // Solo procesar si hay datos
        if(!empty($datos)) {
            foreach($datos as $item){
                $fecha_rf2 = ""; 
                if($item->fe_activacion_estatus_mas_reciente){
                    $fecha_r2 = Carbon::parse($item->fe_activacion_estatus_mas_reciente);
                    $fecha_rf2 = $fecha_r2->isoFormat('MM/DD/YYYY');
                }
                
                if($item->estatus_mas_reciente==""){
                    $item->estatus_mas_reciente = "";
                }

                $fecha_r = Carbon::parse($item->fe_creacion);
                $item->fe_creacion = $fecha_r->isoFormat('MM/DD/YYYY');
                
                if($item->estatus_app_siguiente=="" || $item->estatus_app_siguiente== null)
                    $item->estatus_app_siguiente = "";                

                $aux = explode(".",Number::currency((int)$item->nu_precio_total));
                $item->nu_precio_total = $aux[0]; 

                $myprojects= array(
                    $item->co_aplicacion.' '.$item->tx_primer_nombre.' '.$item->tx_primer_apellido,
                    $item->fe_creacion,
                    $item->settername,
                    $item->ownername,
                    $item->tx_estado,
                    $item->tx_ciudad,
                    $fecha_rf2, 
                    $item->estatus_mas_reciente,
                    $item->estatus_app_siguiente,                    
                    $item->nu_precio_total
                );        
                
                array_push($finalProjects,$myprojects);
            }
        } else {
            Session::flash('error_f', 'No hay datos para exportar.');
            return redirect()->route('account',['type'=>$type]);
        }
        
        $export = new ProjectsExport($finalProjects);
        $date = Carbon::now();
        $file = 'projects-'.$date->toDateTimeString().'.xlsx';
        return Excel::download($export, $file);         
    }
    
    public function filterStatusApp($datos, $statusApp = "") 
    {
        $datos = collect($datos);
        
        $estados = I001tEstatusAplicacione::whereIn('co_estatus_aplicacion', 
            $datos->pluck('co_estatus_aplicacion')
        )
        ->where('co_estatus_aplicacion', '<>', 373)  // Excluir código 373
        ->orderBy('in_orden')
        ->get();
        
        // Añadir el atributo 'selected' a cada estado
        return $estados->map(function($item) use ($statusApp) {
            if(strcasecmp($statusApp,"") != 0 && strcasecmp($item->co_estatus_aplicacion, $statusApp) == 0) {
                $item = Arr::add($item, 'selected', true);
            } else {
                $item = Arr::add($item, 'selected', false);
            }
            return $item;
        });
    }
    
    protected function filterAndSelectAnalysts($allProjects, $analyticsParam = "")
    {
        // Si no hay proyectos, devolver array vacío
        if (empty($allProjects)) {
            return [];
        }
        
        // Extraer analistas y propietarios únicos directamente de los proyectos
        
        $uniqueAnalysts = collect($allProjects)
            ->flatMap(function($item) {
                // Crear dos entradas: una para el owner y otra para el setter
                return [
                    // Owner
                    [
                        'co_usuario' => $item->co_usuario_owner,
                        'analista' => $item->ownername,
                    ],
                    // Setter
                    [
                        'co_usuario' => $item->co_usuario_setter,
                        'analista' => $item->settername,
                    ]
                ];
            })
            ->filter(function($analyst) {
                // Eliminar entradas con usuario o nombre vacío
                return !empty($analyst['co_usuario']) && !empty($analyst['analista']);
            })
            ->unique('co_usuario')  // Eliminar duplicados por co_usuario
            ->values()              // Reindexar
            ->map(function($analyst) use ($analyticsParam) {
                // Marcar como seleccionado si coincide con el parámetro recibido
                return (object) [
                    'co_usuario' => $analyst['co_usuario'],
                    'analista' => $analyst['analista'],
                    'selected' => !empty($analyticsParam) && 
                                strcasecmp($analyst['co_usuario'], $analyticsParam) == 0
                ];
            })
            ->sortBy('analista')    // Ordenar por nombre
            ->values()              // Reindexar después de ordenar
            ->all();                // Convertir a array
        
        return $uniqueAnalysts;
    }
    
    protected function eliminarDuplicados($arreglo) 
    {
        // Usar collect() para crear una colección y eliminar duplicados basados en co_usuario
        return collect($arreglo)->unique('co_usuario')->values()->all();
    }

    protected function updateNotificationApp($co_usuario, $co_aplicacion) {
        // Check if there are any unread notifications for this user and application
        $hasUnread = DB::table('c036t_usuarios_notificacion_his')
            ->where('co_usuario', $co_usuario)
            ->where('co_aplicacion', $co_aplicacion)
            ->whereIN('co_tiponoti', [1,6,7])
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
                        AND co_tiponoti IN (1,6,7)
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
                    AND co_tiponoti IN (1,6,7)";

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
            Log::error('Error en la transacción para user ' . $co_usuario . ', app ' . $co_aplicacion . ': ' . $e->getMessage());
            return false;
        }
    }

    public function getNotifications($co_usuario, $co_aplicacion, string $type = 'unread')
    {
        $query = DB::table('c036t_usuarios_notificacion_his')
            ->where('co_usuario', $co_usuario)
            ->where('co_aplicacion', $co_aplicacion)
            ->whereIN('co_tiponoti', [1,6,7]);
            
        if ($type === 'unread') {
            $query->where('bo_visto', false);
        }
        $notifications = $query
            ->orderBy('co_usrnotificahis', 'desc')
            ->orderBy('fe_registro', 'desc')
            ->get();
            //->paginate(10);
        
        return $notifications->map(function ($notification) {
             $notification->fe_registro = Carbon::parse($notification->fe_registro);
             $notification->highlight = false;
             return $notification;
        });
    }

    
        
    public function showNotifications($co_aplicacion, string $type = 'unread')
    {
        $co_usuario = auth()->id(); 
        
        $notifications = $this->getNotifications($co_usuario, $co_aplicacion,$type);
        
        return view('dashboard.account.notify', [
            'notifications' => $notifications,
            'co_aplicacion' => $co_aplicacion,
            'notification_type' => $type 
        ])->render();
    }

    public function countUnreadNotificationsForApplication(int $co_usuario, int $co_aplicacion): int
    {
        $count = DB::table('c036t_usuarios_notificacion_his')
            ->where('co_usuario', $co_usuario)
            ->where('co_aplicacion', $co_aplicacion)
            ->where('bo_visto', false)
            ->count();

        return $count;
    }
    
}