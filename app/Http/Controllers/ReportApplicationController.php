<?php

namespace App\Http\Controllers;

use App\Exports\InstalledExport;
use App\Exports\ApplicationExport;
use Illuminate\Http\Request;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use stdClass;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Number;
use Illuminate\Support\Arr;
use App\Services\Reports\Application;
use App\Services\Reports\Installed;

class ReportApplicationController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    protected $codigoUsuarioLogueado;
    public function __construct(protected Application $application, protected Installed $installed)
    {
        $this->middleware('auth');
        $this->middleware('IsActive');
    }

    public function index(Request $request)
    {
        
        $dataInstalled = $this->installed->getData($request); 
        $dataInstalledFechas = $this->installed->getRango($request);
        $startDate = Carbon::parse($dataInstalledFechas['startDate'])->format('m/d/Y');
        $endDate = Carbon::parse($dataInstalledFechas['endDate'])->format('m/d/Y');
        $appFinal = $this->application->getDataBar($request);
        $startDateApp = Carbon::parse($appFinal['startDate'])->format('m/d/Y');
        $endDateApp = Carbon::parse($appFinal['endDate'])->format('m/d/Y');
        
        if(count($dataInstalled) <= 0 )
        {
            return view('dashboard.report.application')
            ->with('aplicaciones', $appFinal['aplicaciones'])
            ->with('meses', $appFinal['meses'])
            ->with('data', $appFinal['data'])
            ->with('leyenda', $appFinal['leyenda'])
            ->with('type', $appFinal['type'])
            ->with('startDateApp', $startDateApp)
            ->with('endDateApp', $endDateApp)
            ->with('instalaciones', [])
            ->with('startDate', $startDate)
            ->with('endDate', $endDate)
            ->with('message_error', 'No existen instalaciones en el mes actual');
        }
        return view('dashboard.report.application')
        ->with('aplicaciones', $appFinal['aplicaciones'])
        ->with('meses', $appFinal['meses'])
        ->with('data', $appFinal['data'])
        ->with('leyenda', $appFinal['leyenda'])
        ->with('type', $appFinal['type'])
        ->with('startDateApp', $startDateApp)
        ->with('endDateApp', $endDate)
        ->with('instalaciones', $dataInstalled)
        ->with('startDate', $startDate)
        ->with('endDate', $endDate);
        
    }  

    public function application(Request $request)
    {
        $appFinal = $this->application->getDataBar($request);
        $startDateApp = Carbon::parse($appFinal['startDate'])->format('m/d/Y');
        $endDateApp = Carbon::parse($appFinal['endDate'])->format('m/d/Y');
        if (count($appFinal) <= 0)
        {
            return response()->json([
                'meses' => [],
                'data' => [],
                'leyenda' => 'No hay información',
                'startDateApp'  => $startDateApp,
                'endDateApp'   => $endDateApp,            
                'message_error', 'No hay informacion para el rango consultado',
            ], 204);
        }        
        
         return response()->json([
                'aplicaciones' => $appFinal['aplicaciones'],
                'meses' => $appFinal['meses'],
                'data' => $appFinal['data'],
                'leyenda' => $appFinal['leyenda'],
                'startDate'  => $startDateApp,
                'endDate'   => $endDateApp,                    
                'type' => $appFinal['type']
            ], 200);        
    }

    public function installedApp(Request $request)
    {
        
        $installed = $this->installed->getData($request);
        $installedFechas =  $this->installed->getRango($request);      
        $startDate = Carbon::parse($installedFechas['startDate'])->format('m/d/Y');
        $endDate = Carbon::parse($installedFechas['endDate'])->format('m/d/Y');
        if (count($installed) <= 0)
        {
            return response()->json([
                'instalaciones' => $installed, 
                'data' => array(),
                'startDate' => $startDate,
                'endDate' => $endDate,
                'message_error'=> 'No existen instalaciones para el rango consultado',
            ], 200);
        }        
        
         return response()->json([
                'instalaciones' => $installed, 
                'data' => $installed,     
                'startDate' => $startDate,
                'endDate' => $endDate,                           
            ], 200);        
    }

    public function installedExport(Request $request, string $type='teamprojects'){
        
        $installed = $this->installed->getData($request);        
        $finalInstalled = array();
        if(count($installed)>0){
           foreach($installed as $item){
                
                $myInstalled= array(
                    $item->analista,                    
                    $item->oficina,
                    $item->co_aplicaciones,
                    $item->ventas,
                    Number::format($item->monto)                    
                );        
                
                array_push($finalInstalled,$myInstalled);
           }
        }
        
        $export = new InstalledExport($finalInstalled);        
        $date = Carbon::now();
        $file = 'installed-'.$date->toDateTimeString().'.xlsx';
        return Excel::download($export, $file);
    }

    public function applicationExport(Request $request, string $type='teamprojects'){
        
        $aplicaciones = $this->application->getData($request);
        $finalApp = array();
        if(count($aplicaciones)>0){
           foreach($aplicaciones as $item){
                if($item->cantidad == 0)
                    $item->cantidad  = "0";
                $myInstalled= array(
                    $item->meses.'-'.$item->anyo,
                    $item->cantidad,
                    Number::format($item->monto)                    
                );        
                
                array_push($finalApp,$myInstalled);
           }
        }
        
        $export = new ApplicationExport($finalApp);
        $date = Carbon::now();
        $file = 'application-'.$date->toDateTimeString().'.xlsx';
        return Excel::download($export, $file);
         

    }

    public function installedAppFilter(Request $request)
    {
        //$userSales = new UserSales();
        $installed = $this->installed->getData($request);
        $installedFechas =  $this->installed->getRango($request);      
        $startDate = Carbon::parse($installedFechas['startDate'])->format('m/d/Y');
        $endDate = Carbon::parse($installedFechas['endDate'])->format('m/d/Y');
        if (count($installed) <= 0)
        {
            return response()->json([
                'instalaciones' => $installed, 
                'data' => array(),
                'startDate' => $startDate,
                'endDate' => $endDate,
                'message_error'=> 'No existen instalaciones para el rango consultado',
            ], 200);
        }        
        
         return response()->json([
                'instalaciones' => $installed, 
                'data' => $installed,     
                'startDate' => $startDate,
                'endDate' => $endDate,                           
            ], 200);        
    }




}
