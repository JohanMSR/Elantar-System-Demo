<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rules;
use App\Models\C003tCliente;
use App\Models\I004tZipcode;
use Illuminate\Http\RedirectResponse;

use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Models\I010tEstado;
use Illuminate\Support\Facades\Response;
use App\Models\C002tUsuario;
use App\Models\I020tTipoFuenteCliente;
use App\Http\Requests\LeadRequest;
use App\Models\DateHelper;

class StoreController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('IsActive');
    }

    public function index(Request $request){

        $co_usuario_logueado = Auth()->id();
        $leads = $this->getData($request);
        
        
        if(count($leads)<=0){
            Session::flash('error_f', 'No se encontraron Prospectos para listar.');
            $band = 1;//return back();
        }
        //estados del select estados
        $listEstados = I010tEstado::all();
        if(count($listEstados)<=0){
            Session::flash('error_f', 'No se cargaron los estados. Contacte al Administrador.');
            $band = 2;//return back();
        }

        $listFuente = I020tTipoFuenteCliente::all();

        if(count($listFuente)<=0){
            Session::flash('error_f', 'No se cargaron las fuentes. Contacte al Administrador.');
        }

        //combo duenio del proyecto
        
          $datos_duenio = $this->getOwner(); 
                    
            if(count($datos_duenio)<=0){

                Session::flash('error_f', 'No se cargó el combo Dueño del Proyecto. Contacte al Administrador.');
                
            }
                
        return view('dashboard.shop.shop')
        ->with('leads', $leads)
        ->with('listEstados', $listEstados)
        ->with('select_order' ,"")
        ->with('date1' ,"")
        ->with('date2' ,"")
        ->with('select_qbowner', $datos_duenio)
        ->with('select_fuente',$listFuente)
        ->with('usuario_logueado',$co_usuario_logueado);
        

    }

    public function createNewLead(LeadRequest $request):RedirectResponse
    {
        
       DB::beginTransaction();
       try{
            $co_usuario_logueado = Auth()->id();        
            $co_ryve_usuario = C002tUsuario::find($co_usuario_logueado)->co_ryve_usuario; //$co_quick_base_id 

            $cliente = new C003tCliente;
            $cliente->tx_primer_nombre = $request->input('nombre');
            $cliente->tx_segundo_nombre = $request->input('primera_letra_segundo_nombre');
            $cliente->tx_primer_apellido = $request->input('apellido');
            $cliente->tx_email = $request->input('email');
            $cliente->tx_ciudad = $request->input('city');
            $cliente->tx_zip = $request->input('zip');
            $cliente->co_estado = $request->input('state');
            $cliente->tx_direccion1 = $request->input('direccion');
            $cliente->tx_direccion2 = $request->input('direccion2');
            $cliente->tx_telefono = $request->input('telefono');
            $cliente->fe_fecha_cita = $request->input('fecha_cita');        
            $cliente->ho_cita = $request->input('hora_cita');
            $cliente->co_usuario = $co_usuario_logueado;
            //$cliente->co_qb_setter = $co_quick_base_id;
            $cliente->co_qb_setter = $co_ryve_usuario;
            $cliente->co_ryve_setter = $co_ryve_usuario;

            /***********************************************************
                 * El dueno del proyecto es $cliente->co_qb_owner
                 * por compatibilidad con ryeve se mantiene $cliente->co_ryve_owner
            ********************************************************/        
            $cliente->co_qb_owner = $request->input('select_qbowner');
            $cliente->co_ryve_owner = $request->input('select_qbowner');
            $cliente->tx_comentario = $request->input('nota');
            $cliente->co_fuente = $request->input('select_fuente');        
            $resp_client = $cliente->save();
            
            if(!$resp_client){

                DB::rollback();
                Session::flash('error', 'No pudo registrar al Prospecto...');
                return back();
            }

            DB::commit();
            
            Session::flash('success_register', 'Ingresado Nuevo Prospecto con exito!!');
            return redirect('/store/store');

       }catch(\Exception $e){
            DB::rollback();
            Session::flash('error', 'Error al registrar el prospecto: ' . $e->getMessage());
            return back();
       }

    }

    public function editLead(LeadRequest $request):RedirectResponse{
       
        DB::beginTransaction();
       
        $code_lead_edit = $request->input('code_lead_edit');
        $cliente = C003tCliente::find($code_lead_edit);
        
        if(!$cliente){
            DB::rollback();
            Session::flash('error', 'No se encontro al Prospecto...');
            return back();
        }else{

            $co_usuario_logueado = Auth()->id();        
            //$co_quick_base_id = C002tUsuario::find($co_usuario_logueado)->co_quick_base_id;

            $cliente->tx_primer_nombre = $request->input('nombre');
            $cliente->tx_segundo_nombre = $request->input('primera_letra_segundo_nombre');
            $cliente->tx_primer_apellido = $request->input('apellido');
            $cliente->tx_email = $request->input('email');
            $cliente->tx_ciudad = $request->input('city');
            $cliente->tx_zip = $request->input('zip'); //$resp_zip->co_zip;
            $cliente->co_estado = $request->input('state');
            $cliente->tx_direccion1 = $request->input('direccion');
            $cliente->tx_direccion2 = $request->input('direccion2');
            $cliente->tx_telefono = $request->input('telefono');
            $cliente->fe_fecha_cita = $request->input('fecha_cita');
            
            //$cliente->co_qb_setter = $request->input('co_quick_base_id');
            /***********************************************************
             * El dueno del proyecto es $cliente->co_qb_owner
             * por compatibilidad con ryeve se mantiene $cliente->co_ryve_owner
            ********************************************************/
            $cliente->co_ryve_owner = $request->input('select_qbowner');
            $cliente->co_qb_owner = $request->input('select_qbowner');
            $cliente->ho_cita = $request->input('hora_cita');
            $cliente->tx_comentario = $request->input('nota');
            $cliente->co_fuente = $request->input('select_fuente');
            
            $resp_client = $cliente->save();
        }
		
        if(!$resp_client){

            DB::rollback();
            Session::flash('error', 'No pudo actualizar al Prospecto...');
            return back();
        }

        DB::commit();
        
        Session::flash('success_register', 'Actualización con exito!!');
        return redirect('/store/store');
    }

    public function orderListBtn(Request $request){
        
        $co_usuario_logueado = Auth()->id();

        $bandF = 0;

        $data = $this->getData($request);
        
        if(count($data)>0){    
            return Response::json(array('success'=>true,'msg'=>'Prospectos cargados orden# '. $request->input('select_order'), 'data' => $data), 200); 
        }else{
            return Response::json(array('error'=>false,'msg'=>'No se encontraron los Prospectos para el crterio indicado.'), 422);
        }

    }

    public function searchLeads(Request $request){

   
        $co_usuario_logueado = Auth()->id();
        //Validar fechas
        $validateStartDate = DateHelper::validarFecha($request->input('startDate'));
        $validateEndDate = DateHelper::validarFecha($request->input('endDate'));
        
        if($validateStartDate != true  || $validateEndDate != true){
            Session::flash('error_f', 'Debe ingresar un rango de fecha válido.');
            return redirect()->route('shop');
        }

        $data = $this->getData($request);
        
        if(count($data)<=0){
            Session::flash('error_f', 'No existen Prospectos para el rango de fecha indicado.');
            return back();
        }
        
        $listEstados = I010tEstado::all();

        if(count($listEstados)<=0){
            Session::flash('error_f', 'No se cargaron los estados. Contacte al Administrador.');
            return back();
        }

        $listFuente = I020tTipoFuenteCliente::all();

        if(count($listFuente)<=0){
            Session::flash('error_f', 'No se cargaron las fuentes. Contacte al Administrador.');
            return back();
        }

   
            $datos_duenio = $this->getOwner();        
            if(count($datos_duenio)<=0){

                Session::flash('error_f', 'No se cargó el combo Dueño del Proyecto. Contacte al Administrador.');
                return back();
            }
   
        return view('dashboard.shop.shop')
        ->with('leads', $data)
        ->with('listEstados', $listEstados)
        ->with('select_order' ,$request->input('select_order'))
        ->with('date1' ,$request->input('startDate'))
        ->with('date2' ,$request->input('endDate'))
        ->with('select_qbowner', $datos_duenio)
        ->with('select_fuente',$listFuente)
        ->with('usuario_logueado',$co_usuario_logueado);
               
   }

   public function armarRequestPreclasificacion(Request $request){
        
        $co_cliente = $request->input('co_cliente');
        $sql = '
            SELECT 
            c003t_clientes.tx_direccion1 AS address_address,
            c003t_clientes.tx_direccion2 AS address_address2,
            c003t_clientes.tx_ciudad AS address_city,
            c003t_clientes.tx_estado AS address_state,
            c003t_clientes.tx_zip AS address_zip,
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
             c003t_clientes.co_ryve_setter,
             
             c003t_clientes.ho_cita,
             c003t_clientes.tx_comentario,
             c003t_clientes.co_fuente

        FROM 
            c003t_clientes 
        JOIN 
            c002t_usuarios ON c002t_usuarios.co_usuario = c003t_clientes.co_usuario
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
            
            $aux1 = env('PRECUALIFICACION').$url;
            $aux2 = env('PAYMENTAUTORIZACION').$url;
            $aux3 = env('SITEPHOTOS').$url;
            $aux4 = env('VERIFICATIONDOCUMENTS').$url;
            $aux5 = env('REFERRED').$url;
            
            $base_url = [$aux1, $aux2, $aux3, $aux4, $aux5];
                                                     
            return Response::json(array('success'=>true,'msg'=>'Forma construida.', 'datos' => $base_url), 200); 
       
        }else{

            return Response::json(array('error'=>false,'msg'=>'No se pudo armar las solicitudes para este Cliente.'), 422);
        }

   }

  

   protected function rangoFecha(Request $request){
    
    $fecha1 = $request->input('startDate') ? $request->input('startDate') : ""; 
    $fecha2 = $request->input('endDate') ? $request->input('endDate') : "";
    $rangoFechaCita = array();
    if (strcasecmp($fecha1,"")!= 0 && strcasecmp($fecha2,"")==0){
              $rangoFechaCita = array(
            'startDate' => Carbon::parse($fecha1)->isoFormat('Y-MM-DD'),
           'endDate' => Carbon::parse($fecha1)->addMonth()->isoFormat('Y-MM-DD')
        );   
    }

    if (strcasecmp($fecha1,"")== 0 && strcasecmp($fecha2,"")!=0){
        $rangoFechaCita = array(
           'startDate' => Carbon::parse($fecha2)->subMonth()->isoFormat('Y-MM-DD'),
           'endDate' => Carbon::parse($fecha2)->isoFormat('Y-MM-DD')
        );   
    }

    if (strcasecmp($fecha1,"")!= 0 && strcasecmp($fecha2,"")!=0){
        $rangoFechaCita = array(
            'startDate' => Carbon::parse($fecha1)->isoFormat('Y-MM-DD'),
            'endDate' =>  Carbon::parse($fecha2)->isoFormat('Y-MM-DD')
         );   
    }
    
    return $rangoFechaCita;

    
   }

   protected function getOrder(Request $request){
        
        $inputOrder = $request->input('select_order') ? $request->input('select_order') : 0;    
        
        $order =  " ORDER BY cl1.co_cliente DESC";
       
        if($inputOrder == 1){            
            $order = " ORDER BY cl1.fe_ultima_act DESC";           
        }        
        
        if($inputOrder == 2){            
            $order = " ORDER BY cl1.fe_fecha_cita DESC";          
        }        

        if($inputOrder == 3){            
            $order = " ORDER BY cl1.tx_ciudad ASC";           
        }

        if($inputOrder == 4){
        
            $order = " ORDER BY estado ASC";            
        }
        return $order;
   }

   public function getSQL($usuario){
    
        $sql = "WITH RECURSIVE JerarquiaConNiveles AS (  
            SELECT co_usuario
            FROM c002t_usuarios
            WHERE co_usuario = $usuario and co_estatus_usuario = 1
            UNION ALL
            SELECT o.co_usuario
            FROM c002t_usuarios o
            JOIN JerarquiaConNiveles cte ON o.co_usuario_padre = cte.co_usuario
            ) 
            SELECT DISTINCT
                cl1.co_cliente,
                cl1.tx_primer_nombre,
                cl1.tx_segundo_nombre,
                cl1.tx_primer_apellido,
                cl1.tx_telefono,
                cl1.tx_direccion1,
                cl1.tx_direccion2,
                b.tx_nombre AS estado,
                b.co_estado AS co_estado,
                cl1.tx_ciudad,
                cl1.tx_zip AS zip,
                cl1.fe_fecha_cita,
                cl1.tx_email,
                cl1.bo_tipo_cliente,
                cl1.co_quick_base_id,
                cl1.co_usuario,
                cl1.tx_url_proposals,
                cl1.co_qb_setter,
                cl1.co_qb_owner,
                cl1.co_ryve_owner,
                cl1.co_ryve_setter,
                cl1.ho_cita,
                cl1.tx_comentario,
                cl1.co_fuente,
                cl1.fe_ultima_act,
                usr1.tx_primer_nombre || ' ' || usr1.tx_primer_apellido as analista, 
                usr2.tx_primer_nombre || ' ' || usr2.tx_primer_apellido as agendador 
                FROM 
                    c003t_clientes AS cl1 
                        left JOIN i010t_estados   AS b ON cl1.co_estado = b.co_estado
                        left JOIN c002t_usuarios  AS usr1 ON cl1.co_ryve_owner  = usr1.co_ryve_usuario
                        left JOIN c002t_usuarios  AS usr2 ON cl1.co_ryve_setter = usr2.co_ryve_usuario
                WHERE 
                    (cl1.co_ryve_owner IN (
                    SELECT DISTINCT co_ryve_usuario FROM c002t_usuarios 
                    where co_usuario IN (SELECT DISTINCT co_usuario FROM JerarquiaConNiveles)
                    ) or 
                    cl1.co_ryve_setter IN (
                        SELECT DISTINCT co_ryve_usuario FROM c002t_usuarios 
                    where co_usuario IN (SELECT DISTINCT co_usuario FROM JerarquiaConNiveles)
                    )) 
                    AND cl1.bo_tipo_cliente = 'f'";
        return $sql;            
   }

   protected  function getData(Request $request){
        
        $co_usuario_logueado = Auth()->id();
        
        $sql = $this->getSQL($co_usuario_logueado);
      
        $rangoFechaCita = $this->rangoFecha($request);
        
        if (count($rangoFechaCita) > 0 ){
                $startDate = $rangoFechaCita['startDate'];
                $endDate = $rangoFechaCita['endDate'];
                $sql = $sql ." AND (cl1.fe_creacion BETWEEN DATE('$startDate') AND DATE('$endDate'))";
        } 

        $order = $this->getOrder($request);

        $sql = $sql.$order;
        
        $data = DB::select($sql);
        
        if(count($data) > 0){
            foreach($data as $item){
                if($item->ho_cita !=null){
                    $horaCita = Carbon::parse($item->ho_cita);
                    $turno =  $horaCita->format('A');
                    $item->ho_cita = $horaCita->format('h:i').' '.$turno;
                }
            }           
        }
        
        return $data;
    }

    protected function getOwner()
    {
 
        $co_usuario_logueado = Auth()->id();
        $co_usuario_padre = C002tUsuario::find($co_usuario_logueado)->co_usuario_padre;

        $sql = "SELECT 
            co_usuario,
            CASE
                WHEN co_usuario = $co_usuario_logueado
                THEN 'Yo'
                ELSE tx_primer_nombre
            END AS tx_primer_nombre,
            CASE
                WHEN co_usuario = $co_usuario_logueado
                THEN 'Mismo'
                ELSE tx_primer_apellido
            END AS tx_primer_apellido,
            co_quick_base_id, co_ryve_usuario, co_estatus_usuario

            FROM c002t_usuarios 
            WHERE (co_usuario_padre = $co_usuario_padre OR co_usuario = $co_usuario_logueado OR co_usuario = $co_usuario_padre) and (co_estatus_usuario = 1) or (co_usuario IN (
            WITH RECURSIVE JerarquiaConNiveles AS (
                SELECT co_usuario
                FROM c002t_usuarios
                WHERE co_usuario_padre = $co_usuario_logueado and co_estatus_usuario = 1
                UNION ALL
                SELECT o.co_usuario
                FROM c002t_usuarios o
                JOIN JerarquiaConNiveles cte ON o.co_usuario_padre = cte.co_usuario
            ) 
            SELECT * FROM JerarquiaConNiveles) or co_usuario = $co_usuario_logueado)

            ORDER BY
            CASE
                WHEN co_usuario = $co_usuario_logueado
                THEN 0  -- El usuario padre (3) va primero
                WHEN co_usuario = $co_usuario_padre
                THEN 1  -- El usuario 179 va segundo
                ELSE 2  -- El resto de usuarios van después
            END,
            tx_primer_nombre";

            $datos_duenio = DB::select($sql);
            return $datos_duenio;
    }

    public function searchText($co_usuario_logueado,$busqueda)
    {
        
            $sql = $this->getSQL($co_usuario_logueado);                
            
            if(strcasecmp($busqueda,"")!=0){
            
                $searchWords = explode(' ', $busqueda);
                
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
        
        $data = DB::select($sql);
        return $data;
    }
    public function searchTextLeads(Request $request){
        $co_usuario_logueado = Auth()->id();
        
        $validated = $request->validate([
            'search' => 'nullable|string'
        ], [
            'search.string' => 'El término de búsqueda debe ser texto.'
        ]);
    
        $busqueda = strtolower($validated['search']);
        $busqueda = str_replace("'", "''", $busqueda); // Escape single quotes
        if(!$busqueda){
            Session::flash('error_f', 'Debe proporcionar un término de búsqueda.');
            $band = 2;//return back();
        }
        
        $leads = $this->searchText($co_usuario_logueado,$busqueda);
        
        
        if(count($leads)<=0){
            Session::flash('error_f', 'No se encontraron Prospectos para listar.');
            $band = 1;//return back();
        }
        //estados del select estados
        $listEstados = I010tEstado::all();
        if(count($listEstados)<=0){
            Session::flash('error_f', 'No se cargaron los estados. Contacte al Administrador.');
            $band = 2;//return back();
        }

        $listFuente = I020tTipoFuenteCliente::all();

        if(count($listFuente)<=0){
            Session::flash('error_f', 'No se cargaron las fuentes. Contacte al Administrador.');
        }

        //combo duenio del proyecto
        
          $datos_duenio = $this->getOwner(); 
                    
            if(count($datos_duenio)<=0){

                Session::flash('error_f', 'No se cargó el combo Dueño del Proyecto. Contacte al Administrador.');
                
            }
                
        return view('dashboard.shop.shop')
        ->with('leads', $leads)
        ->with('listEstados', $listEstados)
        ->with('select_order' ,"")
        ->with('date1' ,"")
        ->with('date2' ,"")
        ->with('select_qbowner', $datos_duenio)
        ->with('select_fuente',$listFuente)
        ->with('usuario_logueado',$co_usuario_logueado);
    }
}
