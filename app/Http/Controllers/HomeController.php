<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\View\View;

use App\Models\C002tUsuario;
use App\Models\C001tAplicacione;
use App\Models\I008tOficina;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use stdClass;
use App\Services\Reports\Application;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use App\Services\InstalationOrderService;
use Illuminate\Support\Facades\Auth; // ← Agregar este import también

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(protected Application $application,protected InstalationOrderService $instalationOrderService)
    {
        $this->middleware('auth');
        $this->middleware('IsActive');
    }

    /**
     * Display the dashboard home page.
     *
     * @param  Request $request
     * @return View
     */
    public function index(Request $request): View
    {
       
        //Crear la sesión para el total de órdenes de instalación
        $totalOrdenesInstalacion = $this->instalationOrderService->getTotalOrdenesUsuario();        
        session()->put('total_ordenes_instalacion', $totalOrdenesInstalacion);
        
        $co_usuario_logueado = Auth()->id();
        $roles_usuarios = C002tUsuario::find($co_usuario_logueado)->c014t_usuarios_roles()->get();
        
        $vista = $request->get('vista', 'personal');
        // Validar que la vista sea una de las permitidas
        if (!in_array($vista, ['personal', 'equipo', 'oficina', 'global'])) {
            $vista = 'personal';
        }
        
        if (count($roles_usuarios) <= 0) {
            return $this->renderEmptyDashboard("Al parecer hay un problema con los roles", false, $vista, 'Sin Rol', 0);
        }

        $userRole = $this->determineUserRole($roles_usuarios);
        if (!$userRole) {
            return $this->renderEmptyDashboard("Al parecer hay un problema verificando los roles", false, $vista, 'Sin Rol', 0);
        }

        // Si es instalador (rol 20), mostrar dashboard específico para instaladores
        if ($userRole['co_rol'] === 20) {
            return $this->renderInstallerDashboard($co_usuario_logueado, $userRole);
        }

        $officeInfo = $this->getUserOfficeInfo($co_usuario_logueado);
        if (!$officeInfo) {
            return $this->renderEmptyDashboard("Al parecer hay un problema con las oficinas", false, $vista, $userRole['rol'], $userRole['co_rol']);
        }
        
        // Verificar si el usuario tiene equipo (solo para roles específicos)
        $hasTeam = true; // Por defecto mostrar el botón
        if (in_array($userRole['co_rol'], [1, 2, 11])) { // Analista, Analista Sr., Estudiante
            // Verificar si tiene subordinados Y si el equipo tiene ventas en el año
            $hasSubordinates = $this->userHasTeam($co_usuario_logueado);
            $teamYearlyRevenue = $this->getTeamYearlyRevenue($co_usuario_logueado);
            $hasTeam = $hasSubordinates && $teamYearlyRevenue > 0;
            

        }
        
        $dashboardData = $this->getDashboardMetrics($co_usuario_logueado, $vista);
        $events = $this->getEvents();

        return view('dashboard.home.home')
            ->with('meses', $dashboardData['meses'] ?? [])
            ->with('data', $dashboardData['data'] ?? [])
            ->with('leyenda', $dashboardData['leyenda'] ?? "No hay información")
            ->with('data_torta', $dashboardData['data_torta'] ?? [])
            ->with('leyenda_torta', $dashboardData['leyenda_torta'] ?? "No hay información")
            ->with('sub_leyenda_torta', $dashboardData['sub_leyenda_torta'] ?? "No hay información")
            ->with('eventos', $events['eventos'] ?? [])
            ->with('eventos2', $events['eventos2'] ?? [])
            ->with('rol', $userRole['rol'])
            ->with('rol_co', $userRole['co_rol'] ?? 0)
            ->with('vista_actual', $vista)
            ->with('has_team', $hasTeam)
            ->with('startDate', Carbon::now()->startOfYear()->format('Y-m-d'))
            ->with('endDate', Carbon::now()->endOfYear()->format('Y-m-d'));
    }

    /**
     * Get dashboard data based on user role and view type.
     *
     * @param  int    $co_usuario
     * @param  string $vista
     * @return array
     */
    private function getDashboardMetrics(int $co_usuario, string $vista): array
    {
        $rol_co = session('rol_userlogin_co');
        $metrics = [];

        // Obtener datos para el gráfico de pie
        if ($vista === 'personal') {
            $metrics['pieChart'] = $this->getTop10TeamMembers($co_usuario);
            $metrics['pieChartLegend'] = 'Top 10 Miembros del Equipo';
        } elseif ($vista === 'equipo') {
            $metrics['pieChart'] = $this->getTop10TeamMembers($co_usuario);
            $metrics['pieChartLegend'] = 'Top 10 Miembros del Equipo';
        } elseif ($vista === 'oficina') {
            $metrics['pieChart'] = $this->getTop10TeamMembers($co_usuario);
            $metrics['pieChartLegend'] = 'Top 10 Miembros de la Oficina';
        } elseif ($vista === 'global') {
            $metrics['pieChart'] = $this->getTop3Offices($vista);
            $metrics['pieChartLegend'] = 'Top 3 Oficinas';
        }

        // Obtener datos para el gráfico de barras
        $metrics['barChart'] = $this->getVolumenVentasComparativo($co_usuario, $vista);

        return $metrics;
    }

    /**
     * Determine user role and set session variables.
     *
     * @param  Collection $roles_usuarios
     * @return array|null
     */
    private function determineUserRole(Collection $roles_usuarios): ?array
    {
        foreach ($roles_usuarios as $role) {
            if (in_array($role->co_rol, [11, 0, 1, 2, 3, 4, 5, 6, 7, 8, 20])) {
                // Verificar si es Director (rol 7) con más de una oficina
                if ($role->co_rol == 7 && $this->directorHasMultipleOffices(Auth()->id())) {
                    // Usar lógica de Director Regional pero mantener el título de Director en el menú
                    session(['rol_userlogin_co' => 8]);  // Para la lógica interna (consultas)
                    session(['rol_userlogin' => 'Director']);  // Para mostrar en el menú
                    return ['rol' => 'Director', 'co_rol' => 8];  // co_rol 8 para las consultas, pero mostrar "Director"
                }
                
                session(['rol_userlogin_co' => $role->co_rol]);
                $rol = $this->getRoleName($role->co_rol);
                session(['rol_userlogin' => $rol]);
                return ['rol' => $rol, 'co_rol' => $role->co_rol];
            } elseif (in_array($role->co_rol, [9, 10])) {
                session(['rol_userlogin_co' => $role->co_rol]);
                $rol = $this->getRoleName($role->co_rol);
                session(['rol_userlogin' => $rol]);
                return ['rol' => $rol, 'co_rol' => $role->co_rol];
            }
        }
        return null;
    }

    /**
     * Verificar si un Director tiene más de una oficina en su equipo
     *
     * @param  int $co_usuario
     * @return bool
     */
    private function directorHasMultipleOffices(int $co_usuario): bool
    {
        $sql = "-- Total de oficinas involucradas en mi equipo
            WITH RECURSIVE equipo AS (
                -- Construir el equipo incluyendo el usuario líder y sus dependientes
                SELECT co_usuario FROM c002t_usuarios WHERE co_usuario = :co_usuario
                UNION ALL
                SELECT c.co_usuario FROM c002t_usuarios c 
                INNER JOIN equipo e ON c.co_usuario_padre = e.co_usuario
            )
            SELECT 
                COUNT(DISTINCT uo.co_oficina) AS total_oficinas
            FROM c012t_usuarios_oficinas uo
            JOIN equipo eq ON uo.co_usuario = eq.co_usuario
            JOIN i008t_oficinas o ON uo.co_oficina = o.co_oficina";
            
        $result = DB::select($sql, ['co_usuario' => $co_usuario]);
        
        return isset($result[0]) && $result[0]->total_oficinas > 1;
    }

    /**
     * Verificar si un usuario tiene equipo (subordinados)
     *
     * @param  int $co_usuario
     * @return bool
     */
    private function userHasTeam(int $co_usuario): bool
    {
        $sql = "SELECT COUNT(*) as total_subordinados 
                FROM c002t_usuarios 
                WHERE co_usuario_padre = :co_usuario";
                
        $result = DB::select($sql, ['co_usuario' => $co_usuario]);
        
        $hasTeam = isset($result[0]) && $result[0]->total_subordinados > 0;
        

        
        return $hasTeam;
    }

    /**
     * Obtener las ventas anuales del equipo para verificar si tiene actividad
     *
     * @param  int $co_usuario
     * @return float
     */
    private function getTeamYearlyRevenue(int $co_usuario): float
    {

        $sql = "WITH RECURSIVE equipo AS (
            SELECT co_usuario FROM c002t_usuarios WHERE co_usuario = :co_usuario
            UNION ALL
            SELECT c.co_usuario FROM c002t_usuarios c INNER JOIN equipo e ON c.co_usuario_padre = e.co_usuario
        ), aplicaciones_distintas AS (
            SELECT DISTINCT ON (co_aplicacion) 
                co_aplicacion, fe_activacion_estatus
            FROM c026t_aplicaciones_estatus_historial
            WHERE co_estatus_aplicacion = 366
            ORDER BY co_aplicacion, fe_activacion_estatus
        )
        SELECT COALESCE(SUM(a.nu_precio_total), 0) AS monto_total_ventas
        FROM c001t_aplicaciones a
        JOIN aplicaciones_distintas ad 
            ON a.co_aplicacion = ad.co_aplicacion 
        INNER JOIN equipo eq ON a.co_usuario = eq.co_usuario 	
        WHERE EXTRACT(YEAR FROM ad.fe_activacion_estatus) = EXTRACT(YEAR FROM CURRENT_DATE)
        AND a.co_estatus_aplicacion <> 372
        AND a.co_usuario <> :co_usuario_exclude";
        
        $result = DB::select($sql, ['co_usuario' => $co_usuario, 'co_usuario_exclude' => $co_usuario]);
        
        return isset($result[0]) ? (float)$result[0]->monto_total_ventas : 0;
    }

    /**
     * Get role name based on role code.
     *
     * @param  int $co_rol
     * @return string
     */
    private function getRoleName(int $co_rol): string
    {
        return match($co_rol) {
            1 => "Analista",
            2 => "Analista Sr.",
            3 => "Mentor",
            4 => "Mentor Sr.",
            5 => "Jr. Manager",
            6 => "Sr. Manager",
            7 => "Director",
            8 => "Dir. Regional",
            0 => "Sin Rol definido",
            11 => "Estudiante",
            9 => "Master",
            10 => "Embajador",
            20 => "Instalador",
            default => "..."
        };
    }

    /**
     * Get user office information.
     *
     * @param  int $co_usuario
     * @return array|null
     */
    private function getUserOfficeInfo(int $co_usuario): ?array
    {
        $sql = 'select "Office_City" as office_city from c002t_usuarios where co_usuario = ' . $co_usuario;
        $resp_oficina = DB::select($sql);
        
        if (count($resp_oficina) <= 0) {
            return null;
        }

        return [
            'leyenda' => "Aplicaciones Instaladas Por Mes Últimos 12 Meses",
            'leyenda_torta' => "Top 10 de Mi oficina " . $resp_oficina[0]->office_city . " últimos 3 meses",
            'sub_leyenda_torta' => ""
        ];
    }

    /**
     * Get events data.
     *
     * @return array
     */
    private function getEvents(): array
    {
        $sql = "SELECT
            e.co_evento,
    e.tx_titulo,
    e.tx_descripcion,
    e.fe_registro,
    e.bo_prioridad
FROM c020t_eventos e 
        INNER JOIN c032t_adjunto_evento as B1 on (B1.co_evento=e.co_evento)
ORDER BY e.in_orden_evento DESC, B1.in_orden_adj ASC";
        
        $eventos = DB::select($sql);
        
        $eventos2 = DB::select(
            "SELECT
            e.co_evento AS codigo_general,
            e.tx_titulo,
            e.tx_descripcion,
            e.fe_registro,
            e.bo_prioridad
        FROM c020t_eventos e
        ORDER BY e.in_orden_evento DESC"
        );
        
        $eventosCollection = new Collection($eventos2);
        
        $eventosCollection = $eventosCollection->map(
            function ($evento) {
                $adjuntos = DB::table('c032t_adjunto_evento')
                    ->select('tx_url_adj', 'tipo_adjunto')
                    ->where('co_evento', $evento->codigo_general)
                    ->orderBy('in_orden_adj', 'asc')
                    ->get();

                $evento->adjuntos = $adjuntos;
                return $evento;
            }
        );
        
        return [
            'eventos' => $eventos,
            'eventos2' => $eventosCollection
        ];
    }

    /**
     * Render installer dashboard with fake data.
     *
     * @param  int   $co_usuario
     * @param  array $userRole
     * @return View
     */
    private function renderInstallerDashboard(int $co_usuario, array $userRole): View
    {
        // Data falsa para el dashboard del instalador
        $installerData = $this->getInstallerData($co_usuario);
        
        return view('dashboard.installer.dashboard')
            ->with('installerData', $installerData)
            ->with('rol', $userRole['rol'])
            ->with('rol_co', $userRole['co_rol'])
            ->with('usuario_id', $co_usuario);
    }

    /**
     * Obtener datos falsos para el dashboard del instalador
     *
     * @param  int $co_usuario
     * @return array
     */
    private function getInstallerData(int $co_usuario): array
    {
        return [
            'ordenes_asignadas' => [
                [
                    'id' => 'OI-2024-001',
                    'cliente' => 'Juan Pérez',
                    'direccion' => 'Av. Principal 123, Caracas',
                    'telefono' => '+58 414-1234567',
                    'fecha_programada' => '2024-01-15',
                    'hora_programada' => '10:00 AM',
                    'estatus' => 'Programada',
                    'tipo_instalacion' => 'Filtro de Agua',
                    'costo_estimado' => 150.00,
                    'materiales' => [
                        ['nombre' => 'Filtro Principal', 'cantidad' => 1],
                        ['nombre' => 'Tuberías PVC', 'cantidad' => 3],
                        ['nombre' => 'Conectores', 'cantidad' => 5]
                    ]
                ],
                [
                    'id' => 'OI-2024-002',
                    'cliente' => 'María González',
                    'direccion' => 'Calle 45 #78-90, Valencia',
                    'telefono' => '+58 412-9876543',
                    'fecha_programada' => '2024-01-16',
                    'hora_programada' => '2:00 PM',
                    'estatus' => 'En Progreso',
                    'tipo_instalacion' => 'Sistema Completo',
                    'costo_estimado' => 350.00,
                    'materiales' => [
                        ['nombre' => 'Bomba de Agua', 'cantidad' => 1],
                        ['nombre' => 'Tanque de Presión', 'cantidad' => 1],
                        ['nombre' => 'Filtros Múltiples', 'cantidad' => 3]
                    ]
                ],
                [
                    'id' => 'OI-2024-003',
                    'cliente' => 'Carlos Rodríguez',
                    'direccion' => 'Urbanización Los Naranjos, Casa 25',
                    'telefono' => '+58 426-5551234',
                    'fecha_programada' => '2024-01-17',
                    'hora_programada' => '9:00 AM',
                    'estatus' => 'Pendiente',
                    'tipo_instalacion' => 'Mantenimiento',
                    'costo_estimado' => 75.00,
                    'materiales' => [
                        ['nombre' => 'Filtros de repuesto', 'cantidad' => 2],
                        ['nombre' => 'Limpiador especializado', 'cantidad' => 1]
                    ]
                ]
            ],
            'estadisticas' => [
                'ordenes_completadas_mes' => 12,
                'ordenes_pendientes' => 3,
                'satisfaccion_cliente' => 4.8,
                'tiempo_promedio_instalacion' => '2.5 horas'
            ],
            'gastos_recientes' => [
                [
                    'fecha' => '2024-01-10',
                    'concepto' => 'Transporte',
                    'monto' => 25.00,
                    'orden_id' => 'OI-2024-001'
                ],
                [
                    'fecha' => '2024-01-12',
                    'concepto' => 'Materiales adicionales',
                    'monto' => 45.50,
                    'orden_id' => 'OI-2024-002'
                ]
            ]
        ];
    }

    /**
     * Render empty dashboard with error message.
     *
     * @param  string $message  
     * @param  bool   $hasTeam
     * @param  string $vista
     * @param  string $rol
     * @param  int    $rol_co
     * @return View
     */
    private function renderEmptyDashboard(string $message, bool $hasTeam = true, string $vista = 'personal', string $rol = 'Sin Rol', int $rol_co = 0): View
    {
        return view('dashboard.home.home')
            ->with('meses', [])
            ->with('data', [])
            ->with('leyenda', "No hay información")
            ->with('data_torta', [])
            ->with('leyenda_torta', "No hay información")
            ->with('sub_leyenda_torta', "No hay información")
            ->with('eventos', [])
            ->with('eventos2', [])
            ->with('rol', $rol)
            ->with('rol_co', $rol_co)
            ->with('vista_actual', $vista)
            ->with('has_team', $hasTeam)
            ->with('startDate', Carbon::now()->startOfYear()->format('Y-m-d'))
            ->with('endDate', Carbon::now()->endOfYear()->format('Y-m-d'))
            ->with('message_error', $message);
    }

    function calcDataGraficaXmesMisVentas($mes, $cod_usuario)
    {

        $resp = C001tAplicacione::where('co_usuario', '=', $cod_usuario)
            ->where('co_estatus_aplicacion', '=', 369) //venta_completada
            ->whereMonth('fe_instalacion', '=', $mes)
            ->sum('nu_precio_total');
        return $resp;
    }

    public function aquafeelcrm()
    {
        return view('dashboard.home.aquafeelcrm');
    }



    /**
     * Obtener el Total Revenue según el rol y vista
     */
    private function getTotalRevenue($co_usuario, $vista = 'personal')
    {
        // Obtener el rol del usuario
        $rol_co = session('rol_userlogin_co');
        // Caso especial para vista global
        if ($vista === 'personal') {
            $sql = "WITH aplicaciones_distintas AS (
                SELECT DISTINCT ON (co_aplicacion) 
                    co_aplicacion, fe_activacion_estatus
                FROM c026t_aplicaciones_estatus_historial
                WHERE co_estatus_aplicacion = 366
                ORDER BY co_aplicacion, fe_activacion_estatus
            )
            SELECT COALESCE(SUM(a.nu_precio_total), 0) AS monto_total_ventas
            FROM c001t_aplicaciones a
            JOIN aplicaciones_distintas ad 
                ON a.co_aplicacion = ad.co_aplicacion
            WHERE a.co_usuario = :co_usuario 
            AND ad.fe_activacion_estatus >= DATE_TRUNC('month', ad.fe_activacion_estatus) + INTERVAL '2 days'
            AND ad.fe_activacion_estatus < DATE_TRUNC('month', ad.fe_activacion_estatus) + INTERVAL '1 month' + INTERVAL '2 days'
            AND EXTRACT(YEAR FROM ad.fe_activacion_estatus) = EXTRACT(YEAR FROM CURRENT_DATE)
            AND a.co_estatus_aplicacion <> 372";
            
            return DB::select($sql, ['co_usuario' => $co_usuario])[0]->monto_total_ventas ?? 0;
        }
        elseif ($vista === 'equipo') {
            $sql = "WITH RECURSIVE equipo AS (
                SELECT co_usuario FROM c002t_usuarios WHERE co_usuario = :co_usuario
                UNION ALL
                SELECT c.co_usuario FROM c002t_usuarios c INNER JOIN equipo e ON c.co_usuario_padre = e.co_usuario
            ), aplicaciones_distintas AS (
                SELECT DISTINCT ON (co_aplicacion) 
                    co_aplicacion, fe_activacion_estatus
                FROM c026t_aplicaciones_estatus_historial
                WHERE co_estatus_aplicacion = 366
                ORDER BY co_aplicacion, fe_activacion_estatus
            )
            SELECT COALESCE(SUM(a.nu_precio_total), 0) AS monto_total_ventas
            FROM c001t_aplicaciones a
            JOIN aplicaciones_distintas ad 
                ON a.co_aplicacion = ad.co_aplicacion 
            INNER JOIN equipo eq ON a.co_usuario = eq.co_usuario 	
            WHERE ad.fe_activacion_estatus >= DATE_TRUNC('month', ad.fe_activacion_estatus) + INTERVAL '2 days'
            AND ad.fe_activacion_estatus < DATE_TRUNC('month', ad.fe_activacion_estatus) + INTERVAL '1 month' + INTERVAL '2 days'
            AND EXTRACT(YEAR FROM ad.fe_activacion_estatus) = EXTRACT(YEAR FROM CURRENT_DATE)
            AND a.co_estatus_aplicacion <> 372
            AND a.co_usuario <> :co_usuario_exclude";
            
            return DB::select($sql, ['co_usuario' => $co_usuario, 'co_usuario_exclude' => $co_usuario])[0]->monto_total_ventas ?? 0;
        } 
        elseif ($vista === 'oficina') {
            if ($rol_co == 8) {
                $sql = "WITH mis_oficinas AS (
                        SELECT co_oficina 
                        FROM c018t_usr_ofic_reg 
                        WHERE co_usuario = :co_usuario
                    ), 
                    usuarios_de_mis_oficinas AS (
                        SELECT DISTINCT uo.co_usuario
                        FROM c012t_usuarios_oficinas uo
                        INNER JOIN mis_oficinas mo ON uo.co_oficina = mo.co_oficina
                    ), 
                    aplicaciones_distintas AS (
                        SELECT DISTINCT ON (co_aplicacion) 
                            co_aplicacion, fe_activacion_estatus
                        FROM c026t_aplicaciones_estatus_historial
                        WHERE co_estatus_aplicacion = 366
                          AND fe_activacion_estatus >= DATE_TRUNC('year', CURRENT_DATE) + INTERVAL '2 days'
                          AND fe_activacion_estatus <  DATE_TRUNC('year', CURRENT_DATE) + INTERVAL '1 year' + INTERVAL '2 days'
                        ORDER BY co_aplicacion, fe_activacion_estatus
                    ),
                    ventas_por_oficina AS (
                        SELECT 
                            uo.co_oficina,
                            SUM(a.nu_precio_total) AS monto_total_ventas,
                            COUNT(a.co_aplicacion) AS total_aplicaciones_vendidas
                        FROM c001t_aplicaciones a
                        JOIN aplicaciones_distintas ad ON a.co_aplicacion = ad.co_aplicacion
                        INNER JOIN usuarios_de_mis_oficinas um ON a.co_usuario = um.co_usuario
                        INNER JOIN c012t_usuarios_oficinas uo ON a.co_usuario = uo.co_usuario
                        WHERE a.co_estatus_aplicacion <> 372
                        GROUP BY uo.co_oficina
                    )
                    SELECT 
                        SUM(vpo.monto_total_ventas) AS monto_total_ventas_general
                    FROM ventas_por_oficina vpo
                    JOIN i008t_oficinas o ON vpo.co_oficina = o.co_oficina;";
                $result = DB::select($sql, ['co_usuario' => $co_usuario]);
                return $result[0]->monto_total_ventas_general ?? 0;
            } else {
                $sql = "WITH oficina_usuarios AS (
                    -- Obtener la oficina del usuario y sus compañeros de oficina
                    SELECT uo.co_usuario 
                    FROM c012t_usuarios_oficinas uo
                    WHERE uo.co_oficina = (SELECT co_oficina FROM c012t_usuarios_oficinas WHERE co_usuario = :co_usuario)
                ), aplicaciones_distintas AS (
                    -- Identificar aplicaciones únicas que hayan pasado por estatus 366 al menos una vez
                    SELECT DISTINCT ON (co_aplicacion) 
                        co_aplicacion, fe_activacion_estatus
                    FROM c026t_aplicaciones_estatus_historial
                    WHERE co_estatus_aplicacion = 366
                    ORDER BY co_aplicacion, fe_activacion_estatus
                )
                SELECT COALESCE(SUM(a.nu_precio_total), 0) AS monto_total_ventas
                FROM c001t_aplicaciones a
                JOIN aplicaciones_distintas ad ON a.co_aplicacion = ad.co_aplicacion
                INNER JOIN oficina_usuarios ou ON a.co_usuario = ou.co_usuario
                WHERE ad.fe_activacion_estatus >= DATE_TRUNC('month', ad.fe_activacion_estatus) + INTERVAL '2 days'
                AND ad.fe_activacion_estatus < DATE_TRUNC('month', ad.fe_activacion_estatus) + INTERVAL '1 month' + INTERVAL '2 days'
                AND EXTRACT(YEAR FROM ad.fe_activacion_estatus) = EXTRACT(YEAR FROM CURRENT_DATE)
                AND a.co_estatus_aplicacion <> 372";
                
                return DB::select($sql, ['co_usuario' => $co_usuario])[0]->monto_total_ventas ?? 0;
            }
        }
        elseif ($vista === 'global') {
            $sql = "WITH aplicaciones_distintas AS (
                -- Identificar aplicaciones únicas que hayan pasado por estatus 366 al menos una vez
                SELECT DISTINCT ON (co_aplicacion) 
                    co_aplicacion, fe_activacion_estatus
                FROM c026t_aplicaciones_estatus_historial
                WHERE co_estatus_aplicacion = 366
                ORDER BY co_aplicacion, fe_activacion_estatus
            )
            SELECT COALESCE(SUM(a.nu_precio_total), 0) AS monto_total_ventas
            FROM c001t_aplicaciones a
            JOIN aplicaciones_distintas ad ON a.co_aplicacion = ad.co_aplicacion    
            WHERE ad.fe_activacion_estatus >= DATE_TRUNC('month', ad.fe_activacion_estatus) + INTERVAL '2 days'
            AND ad.fe_activacion_estatus < DATE_TRUNC('month', ad.fe_activacion_estatus) + INTERVAL '1 month' + INTERVAL '2 days'
            AND EXTRACT(YEAR FROM ad.fe_activacion_estatus) = EXTRACT(YEAR FROM CURRENT_DATE)
            AND a.co_estatus_aplicacion <> 372";
            
            return DB::select($sql)[0]->monto_total_ventas ?? 0;
        }
        return 0;
    }
    /** 
     * Obtener el Total Orders según el rol y vista
     */
    private function getTotalOrders($co_usuario, $vista = 'personal')
    {
        // Obtener el rol del usuario
        $rol_co = session('rol_userlogin_co');
        // Caso especial para vista global
        if ($vista === 'personal') {
            // Caso especial para vista personal
            $sql = "WITH aplicaciones_distintas AS (
                SELECT DISTINCT ON (co_aplicacion) 
                    co_aplicacion, fe_activacion_estatus
                FROM c026t_aplicaciones_estatus_historial
                WHERE co_estatus_aplicacion = 366
                ORDER BY co_aplicacion, fe_activacion_estatus
            )
            SELECT COUNT(*) AS total_ventas
            FROM c001t_aplicaciones a
            JOIN aplicaciones_distintas ad 
                ON a.co_aplicacion = ad.co_aplicacion
            WHERE a.co_usuario = :co_usuario 
            AND ad.fe_activacion_estatus >= DATE_TRUNC('month', ad.fe_activacion_estatus) + INTERVAL '2 days'
            AND ad.fe_activacion_estatus < DATE_TRUNC('month', ad.fe_activacion_estatus) + INTERVAL '1 month' + INTERVAL '2 days'
            AND EXTRACT(YEAR FROM ad.fe_activacion_estatus) = EXTRACT(YEAR FROM CURRENT_DATE)
            AND a.co_estatus_aplicacion <> 372";
            
            return DB::select($sql, ['co_usuario' => $co_usuario])[0]->total_ventas ?? 0;
        } 
        elseif ($vista === 'equipo') {
            $sql = "WITH RECURSIVE equipo AS (
                SELECT co_usuario FROM c002t_usuarios WHERE co_usuario = :co_usuario
                UNION ALL
                SELECT c.co_usuario FROM c002t_usuarios c INNER JOIN equipo e ON c.co_usuario_padre = e.co_usuario
            ), aplicaciones_distintas AS (
                SELECT DISTINCT ON (co_aplicacion) 
                    co_aplicacion, fe_activacion_estatus
                FROM c026t_aplicaciones_estatus_historial
                WHERE co_estatus_aplicacion = 366
                ORDER BY co_aplicacion, fe_activacion_estatus
            )
            SELECT COUNT(*) AS total_ventas
            FROM c001t_aplicaciones a
            JOIN aplicaciones_distintas ad 
                ON a.co_aplicacion = ad.co_aplicacion
            INNER JOIN equipo eq ON a.co_usuario = eq.co_usuario    
            WHERE ad.fe_activacion_estatus >= DATE_TRUNC('month', ad.fe_activacion_estatus) + INTERVAL '2 days'
            AND ad.fe_activacion_estatus < DATE_TRUNC('month', ad.fe_activacion_estatus) + INTERVAL '1 month' + INTERVAL '2 days'
            AND EXTRACT(YEAR FROM ad.fe_activacion_estatus) = EXTRACT(YEAR FROM CURRENT_DATE)
            AND a.co_estatus_aplicacion <> 372
            AND a.co_usuario <> :co_usuario_exclude";
            
            return DB::select($sql, ['co_usuario' => $co_usuario, 'co_usuario_exclude' => $co_usuario])[0]->total_ventas ?? 0;
        }
        elseif ($vista === 'oficina') {
            // Si el usuario es Director Regional (rol 8)
            if ($rol_co == 8) {
                $sql = "WITH mis_oficinas AS (
                        SELECT co_oficina 
                        FROM c018t_usr_ofic_reg 
                        WHERE co_usuario = :co_usuario
                    ), 
                    usuarios_de_mis_oficinas AS (
                        SELECT DISTINCT uo.co_usuario
                        FROM c012t_usuarios_oficinas uo
                        INNER JOIN mis_oficinas mo ON uo.co_oficina = mo.co_oficina
                    ), 
                    aplicaciones_distintas AS (
                        SELECT DISTINCT ON (co_aplicacion) 
                            co_aplicacion, fe_activacion_estatus
                        FROM c026t_aplicaciones_estatus_historial
                        WHERE co_estatus_aplicacion = 366
                          AND fe_activacion_estatus >= DATE_TRUNC('year', CURRENT_DATE) + INTERVAL '2 days'
                          AND fe_activacion_estatus <  DATE_TRUNC('year', CURRENT_DATE) + INTERVAL '1 year' + INTERVAL '2 days'
                        ORDER BY co_aplicacion, fe_activacion_estatus
                    ),
                    ventas_por_oficina AS (
                        SELECT 
                            uo.co_oficina,
                            SUM(a.nu_precio_total) AS monto_total_ventas,
                            COUNT(a.co_aplicacion) AS total_aplicaciones_vendidas
                        FROM c001t_aplicaciones a
                        JOIN aplicaciones_distintas ad ON a.co_aplicacion = ad.co_aplicacion
                        INNER JOIN usuarios_de_mis_oficinas um ON a.co_usuario = um.co_usuario
                        INNER JOIN c012t_usuarios_oficinas uo ON a.co_usuario = uo.co_usuario
                        WHERE a.co_estatus_aplicacion <> 372
                        GROUP BY uo.co_oficina
                    )
                    SELECT 
                        SUM(vpo.total_aplicaciones_vendidas) AS total_aplicaciones_vendidas_general
                    FROM ventas_por_oficina vpo
                    JOIN i008t_oficinas o ON vpo.co_oficina = o.co_oficina;";
                $result = DB::select($sql, ['co_usuario' => $co_usuario]);
                return $result[0]->total_aplicaciones_vendidas_general ?? 0;
            } else {
                $sql = "WITH oficina_usuarios AS (
    -- Obtener la oficina del usuario y sus compañeros de oficina
    SELECT uo.co_usuario, ofi.tx_nombre  
    FROM c012t_usuarios_oficinas uo
    INNER JOIN i008t_oficinas ofi ON ofi.co_oficina = uo.co_oficina   
    WHERE uo.co_oficina = (SELECT co_oficina FROM c012t_usuarios_oficinas WHERE co_usuario = :co_usuario)
),
aplicaciones_distintas AS (
    -- Identificar aplicaciones únicas que hayan pasado por estatus 366 al menos una vez dentro del año actual
    SELECT DISTINCT ON (co_aplicacion) 
        co_aplicacion, fe_activacion_estatus
    FROM c026t_aplicaciones_estatus_historial
    WHERE co_estatus_aplicacion = 366
      AND fe_activacion_estatus >= DATE_TRUNC('year', CURRENT_DATE) + INTERVAL '2 days'
      AND fe_activacion_estatus < DATE_TRUNC('year', CURRENT_DATE) + INTERVAL '1 year' + INTERVAL '2 days'
    ORDER BY co_aplicacion, fe_activacion_estatus
),
ventas_por_oficina AS (
    -- Obtener el volumen de ventas de los usuarios de la misma oficina
    SELECT 
        uo.tx_nombre AS oficina,
        SUM(a.nu_precio_total) AS monto_total_ventas,
        COUNT(a.co_aplicacion) AS total_aplicaciones_vendidas
    FROM c001t_aplicaciones a
    JOIN aplicaciones_distintas ad ON a.co_aplicacion = ad.co_aplicacion
    JOIN oficina_usuarios uo ON a.co_usuario = uo.co_usuario  -- Filtrar por usuarios de la misma oficina
    WHERE a.co_estatus_aplicacion <> 372
    GROUP BY uo.tx_nombre
)
SELECT 
    SUM(vpo.total_aplicaciones_vendidas) AS total_aplicaciones_vendidas
FROM ventas_por_oficina vpo;";
                return DB::select($sql, ['co_usuario' => $co_usuario])[0]->total_aplicaciones_vendidas ?? 0;
            }
        }
        elseif ($vista === 'global') {
            $sql = "WITH aplicaciones_distintas AS (
                -- Identificar aplicaciones únicas que hayan pasado por estatus 366 al menos una vez
                SELECT DISTINCT ON (co_aplicacion) 
                    co_aplicacion, fe_activacion_estatus
                FROM c026t_aplicaciones_estatus_historial
                WHERE co_estatus_aplicacion = 366
                ORDER BY co_aplicacion, fe_activacion_estatus
            )
            SELECT COUNT(a.co_aplicacion) AS cantidad_aplicaciones
            FROM c001t_aplicaciones a
            JOIN aplicaciones_distintas ad ON a.co_aplicacion = ad.co_aplicacion
            WHERE ad.fe_activacion_estatus >= DATE_TRUNC('month', ad.fe_activacion_estatus) + INTERVAL '2 days'
            AND ad.fe_activacion_estatus < DATE_TRUNC('month', ad.fe_activacion_estatus) + INTERVAL '1 month' + INTERVAL '2 days'
            AND EXTRACT(YEAR FROM ad.fe_activacion_estatus) = EXTRACT(YEAR FROM CURRENT_DATE)
            AND a.co_estatus_aplicacion <> 372";
            
            return DB::select($sql)[0]->cantidad_aplicaciones ?? 0;
        }
        

    }

    private function getOrdersByStatus($co_usuario, $vista = 'personal', $periodo = 'anual')
    {
        $rol_co = session('rol_userlogin_co');
        // Caso especial para vista personal
        if ($vista === 'personal') {
            // Determinar el filtro de fecha según el período
            if ($periodo === '90dias') {
                $dateFilter = "a.fe_creacion >= CURRENT_DATE - INTERVAL '90 days'";
            } else {
                $dateFilter = "a.fe_creacion >= DATE_TRUNC('year', CURRENT_DATE) + INTERVAL '2 days'
                               AND a.fe_creacion < DATE_TRUNC('year', CURRENT_DATE) + INTERVAL '1 year' + INTERVAL '2 days'";
            }
            
            $sql = "SELECT 
                e.tx_nombre AS nombre_estatus,
                COUNT(a.co_aplicacion) AS cantidad_aplicaciones
            FROM c001t_aplicaciones a 
            JOIN i001t_estatus_aplicaciones e ON a.co_estatus_aplicacion = e.co_estatus_aplicacion
            WHERE 
                a.co_usuario = :co_usuario and 
                {$dateFilter}
            GROUP BY e.tx_nombre
            ORDER BY cantidad_aplicaciones DESC";
            
            return DB::select($sql, ['co_usuario' => $co_usuario]);
        } 
        elseif ($vista === 'equipo') {
            // Determinar el filtro de fecha según el período
            if ($periodo === '90dias') {
                $dateFilter = "a.fe_creacion >= CURRENT_DATE - INTERVAL '90 days'";
            } else {
                $dateFilter = "a.fe_creacion >= DATE_TRUNC('year', CURRENT_DATE) + INTERVAL '2 days'
                               AND a.fe_creacion < DATE_TRUNC('year', CURRENT_DATE) + INTERVAL '1 year' + INTERVAL '2 days'";
            }
            
            $sql = "WITH RECURSIVE equipo AS (
    -- Usuarios del equipo de trabajo
    SELECT co_usuario FROM c002t_usuarios WHERE co_usuario = :co_usuario
    UNION ALL
    SELECT c.co_usuario FROM c002t_usuarios c 
    INNER JOIN equipo e ON c.co_usuario_padre = e.co_usuario
) 
SELECT 
    e.tx_nombre AS nombre_estatus,
    COUNT(a.co_aplicacion) AS cantidad_aplicaciones
FROM c001t_aplicaciones a 
JOIN i001t_estatus_aplicaciones e ON a.co_estatus_aplicacion = e.co_estatus_aplicacion
INNER JOIN equipo ou ON a.co_usuario = ou.co_usuario  -- Incorporamos la lógica de oficina restringida al equipo
WHERE 
     {$dateFilter}
 and a.co_usuario <> :co_usuario_exclude
GROUP BY e.tx_nombre
ORDER BY cantidad_aplicaciones DESC";
            
            return DB::select($sql, ['co_usuario' => $co_usuario, 'co_usuario_exclude' => $co_usuario]);
        } 
        elseif ($vista === 'oficina') {
            // Si es director regional (rol 8), usar SQL específico
            if ($rol_co == 8) {
                // Determinar el filtro de fecha según el período
                $dateFilter = $periodo === '90dias' 
                    ? "a.fe_creacion >= CURRENT_DATE - INTERVAL '90 days'"
                    : "a.fe_creacion >= DATE_TRUNC('year', CURRENT_DATE) + INTERVAL '2 days'
                       AND a.fe_creacion < DATE_TRUNC('year', CURRENT_DATE) + INTERVAL '1 year' + INTERVAL '2 days'";
                
                $sql = "WITH mis_oficinas AS (
                    SELECT co_oficina 
                    FROM c018t_usr_ofic_reg 
                    WHERE co_usuario = :co_usuario
                ), 
                usuarios_de_mis_oficinas AS (
                    SELECT DISTINCT uo.co_usuario
                    FROM c012t_usuarios_oficinas uo
                    INNER JOIN mis_oficinas mo ON uo.co_oficina = mo.co_oficina
                ), 
                aplicaciones_por_estatus AS (
                    SELECT 
                        e.tx_nombre AS nombre_estatus,
                        COUNT(a.co_aplicacion) AS cantidad_aplicaciones,
                        STRING_AGG(DISTINCT o.tx_nombre, ', ') AS oficinas_involucradas,
                        STRING_AGG(a.co_aplicacion::TEXT || '[' || a.fe_creacion::TEXT || ' - ' || a.co_usuario::TEXT || ']', ', ') AS aplicaciones_involucradas
                    FROM c001t_aplicaciones a
                    JOIN i001t_estatus_aplicaciones e ON a.co_estatus_aplicacion = e.co_estatus_aplicacion
                    INNER JOIN usuarios_de_mis_oficinas um ON a.co_usuario = um.co_usuario
                    INNER JOIN c012t_usuarios_oficinas uo ON a.co_usuario = uo.co_usuario
                    INNER JOIN i008t_oficinas o ON uo.co_oficina = o.co_oficina
                    WHERE {$dateFilter}
                    GROUP BY e.tx_nombre
                )
                SELECT 
                    nombre_estatus,
                    cantidad_aplicaciones,
                    COALESCE(oficinas_involucradas, 'Sin oficina') AS oficinas_involucradas,
                    COALESCE(aplicaciones_involucradas, 'Sin aplicaciones') AS aplicaciones_involucradas
                FROM aplicaciones_por_estatus
                ORDER BY cantidad_aplicaciones DESC";
            } else {
                // Determinar el filtro de fecha según el período
                if ($periodo === '90dias') {
                    $dateFilter = "a.fe_creacion >= CURRENT_DATE - INTERVAL '90 days'";
                } else {
                    $dateFilter = "a.fe_creacion >= DATE_TRUNC('year', CURRENT_DATE) + INTERVAL '2 days'
                                   AND a.fe_creacion < DATE_TRUNC('year', CURRENT_DATE) + INTERVAL '1 year' + INTERVAL '2 days'";
                }
                
                $sql = "WITH oficina_usuarios AS (
                    -- Obtener la oficina del usuario y sus compañeros de oficina
                    SELECT uo.co_usuario, ofi.tx_nombre  
                    FROM c012t_usuarios_oficinas uo
                    INNER JOIN i008t_oficinas as ofi ON (ofi.co_oficina = uo.co_oficina)   
                    WHERE uo.co_oficina = (SELECT co_oficina FROM c012t_usuarios_oficinas WHERE co_usuario = :co_usuario)
                )
                SELECT 
                    e.tx_nombre AS nombre_estatus,
                    COUNT(a.co_aplicacion) AS cantidad_aplicaciones
                FROM c001t_aplicaciones a 
                JOIN i001t_estatus_aplicaciones e ON a.co_estatus_aplicacion = e.co_estatus_aplicacion
                INNER JOIN oficina_usuarios ou ON a.co_usuario = ou.co_usuario
                WHERE 
                     {$dateFilter}
                GROUP BY e.tx_nombre
                ORDER BY cantidad_aplicaciones DESC";
            }
            
            return DB::select($sql, ['co_usuario' => $co_usuario]);

        } elseif ($vista === 'global') {
            // Determinar el filtro de fecha según el período
            if ($periodo === '90dias') {
                $dateFilter = "a.fe_creacion >= CURRENT_DATE - INTERVAL '90 days'";
            } else {
                $dateFilter = "a.fe_creacion >= DATE_TRUNC('year', CURRENT_DATE) + INTERVAL '2 days'
                               AND a.fe_creacion < DATE_TRUNC('year', CURRENT_DATE) + INTERVAL '1 year' + INTERVAL '2 days'";
            }
            
            $sql = "SELECT 
                e.tx_nombre AS nombre_estatus,
                COUNT(a.co_aplicacion) AS cantidad_aplicaciones
            FROM c001t_aplicaciones a
            JOIN i001t_estatus_aplicaciones e ON a.co_estatus_aplicacion = e.co_estatus_aplicacion
            WHERE {$dateFilter}
            GROUP BY e.tx_nombre
            ORDER BY cantidad_aplicaciones DESC";
            
            return DB::select($sql);
        }
        // Por ahora retornamos array vacío para otras vistas
        return [];
    }
    /**
     * Obtener volumen de ventas personal año actual vs anterior
     */
    private function getVolumenVentasComparativo($co_usuario, $vista = 'personal')
    {
        // Obtener el rol del usuario
        $rol_co = session('rol_userlogin_co');  
        
        // Caso especial para vista global
        
        if ($vista === 'personal') {
            $sql = "WITH aplicaciones_distintas AS (
                SELECT DISTINCT ON (co_aplicacion)
                    co_aplicacion, fe_activacion_estatus
                FROM c026t_aplicaciones_estatus_historial
                WHERE co_estatus_aplicacion = 366
                ORDER BY co_aplicacion, fe_activacion_estatus ASC
            ),
            meses_anios AS (
                SELECT anio, mes
                FROM (
                    SELECT EXTRACT(YEAR FROM CURRENT_DATE) AS anio, generate_series(1,12) AS mes
                    UNION ALL
                    SELECT EXTRACT(YEAR FROM CURRENT_DATE) - 1 AS anio, generate_series(1,12) AS mes
                ) AS meses_generados
            )
            SELECT
                ma.anio,
                TO_CHAR(ma.mes, 'FM00') AS numero_mes,
                COALESCE(COUNT(a.co_aplicacion), 0) AS cantidad_aplicaciones,
                COALESCE(SUM(a.nu_precio_total), 0) AS monto_total_mensual
            FROM meses_anios ma
            LEFT JOIN aplicaciones_distintas ad
                ON ma.anio = EXTRACT(YEAR FROM ad.fe_activacion_estatus)
                AND ma.mes = EXTRACT(MONTH FROM ad.fe_activacion_estatus)
            LEFT JOIN c001t_aplicaciones a
                ON a.co_aplicacion = ad.co_aplicacion
                AND ad.fe_activacion_estatus >= DATE_TRUNC('month', ad.fe_activacion_estatus) + INTERVAL '2 days'
                AND ad.fe_activacion_estatus < DATE_TRUNC('month', ad.fe_activacion_estatus) + INTERVAL '1 month' + INTERVAL '2 days'
                AND a.co_estatus_aplicacion <> 372
                AND a.co_usuario = :co_usuario
            GROUP BY ma.anio, ma.mes
            ORDER BY ma.anio, ma.mes";
            
            return DB::select(
                $sql, [
                'co_usuario' => $co_usuario
                ]
            );
        } 
        elseif ($vista === 'equipo') {
            $sql = "WITH RECURSIVE equipo AS (
                SELECT co_usuario FROM c002t_usuarios WHERE co_usuario = :co_usuario
                UNION ALL
                SELECT c.co_usuario FROM c002t_usuarios c INNER JOIN equipo e ON c.co_usuario_padre = e.co_usuario
            ), aplicaciones_distintas AS (
                SELECT DISTINCT ON (co_aplicacion) 
                    co_aplicacion, fe_activacion_estatus
                FROM c026t_aplicaciones_estatus_historial
                WHERE co_estatus_aplicacion = 366
                ORDER BY co_aplicacion, fe_activacion_estatus
            ),
            meses_anios AS (
                SELECT anio, mes
                FROM (
                    SELECT EXTRACT(YEAR FROM CURRENT_DATE) AS anio, generate_series(1,12) AS mes
                    UNION ALL
                    SELECT EXTRACT(YEAR FROM CURRENT_DATE) - 1 AS anio, generate_series(1,12) AS mes
                ) AS meses
            )
            SELECT 
                ma.anio,
                TO_CHAR(ma.mes, 'FM00') AS numero_mes,
                COALESCE(COUNT(a.co_aplicacion), 0) AS cantidad_aplicaciones,
                COALESCE(SUM(a.nu_precio_total), 0) AS monto_total_mensual
            FROM meses_anios ma
            LEFT JOIN aplicaciones_distintas ad 
                ON ma.anio = EXTRACT(YEAR FROM ad.fe_activacion_estatus)
                AND ma.mes = EXTRACT(MONTH FROM ad.fe_activacion_estatus)
            LEFT JOIN c001t_aplicaciones a 
                ON a.co_aplicacion = ad.co_aplicacion
                AND ad.fe_activacion_estatus >= DATE_TRUNC('month', ad.fe_activacion_estatus) + INTERVAL '2 days'
                AND ad.fe_activacion_estatus < DATE_TRUNC('month', ad.fe_activacion_estatus) + INTERVAL '1 month' + INTERVAL '2 days'
                AND a.co_estatus_aplicacion <> 372
            INNER JOIN equipo eq ON a.co_usuario = eq.co_usuario  
            WHERE a.co_usuario <> :co_usuario_exclude
            GROUP BY ma.anio, ma.mes
            ORDER BY ma.anio, ma.mes";
            
            return DB::select(
                $sql, [
                'co_usuario' => $co_usuario,
                'co_usuario_exclude' => $co_usuario
                ]
            );
        }
                // Caso especial para Mentors, Mentor Sr. y Jr. Manager en vista de oficina
        elseif ($vista === 'oficina') { // 3=Mentor, 4=Mentor Sr., 5=Jr. Manager
            if ($rol_co == 8) {
                $sql = "WITH mis_oficinas AS (
                            SELECT co_oficina 
                            FROM c018t_usr_ofic_reg 
                            WHERE co_usuario = :co_usuario
                        ),
                        usuarios_de_mis_oficinas AS (
                            SELECT DISTINCT uo.co_usuario
                            FROM c012t_usuarios_oficinas uo
                            INNER JOIN mis_oficinas mo ON uo.co_oficina = mo.co_oficina
                        ),
                        meses_anios AS (
                            SELECT anio, mes_inicio, mes_fin
                            FROM (
                                SELECT 
                                    EXTRACT(YEAR FROM CURRENT_DATE) AS anio,
                                    DATE_TRUNC('month', make_date(EXTRACT(YEAR FROM CURRENT_DATE)::int, generate_series(1,12), 3)) AS mes_inicio,
                                    DATE_TRUNC('month', make_date(EXTRACT(YEAR FROM CURRENT_DATE)::int, generate_series(1,12), 3)) + INTERVAL '1 month' + INTERVAL '1 day' AS mes_fin
                                UNION ALL
                                SELECT 
                                    EXTRACT(YEAR FROM CURRENT_DATE) - 1 AS anio,
                                    DATE_TRUNC('month', make_date(EXTRACT(YEAR FROM CURRENT_DATE)::int - 1, generate_series(1,12), 3)) AS mes_inicio,
                                    DATE_TRUNC('month', make_date(EXTRACT(YEAR FROM CURRENT_DATE)::int - 1, generate_series(1,12), 3)) + INTERVAL '1 month' + INTERVAL '1 day' AS mes_fin
                            ) AS meses
                        ),
                        aplicaciones_distintas AS (
                            SELECT DISTINCT ON (co_aplicacion) 
                                co_aplicacion,
                                DATE_TRUNC('month', fe_activacion_estatus - INTERVAL '2 days') AS mes_asignado
                            FROM c026t_aplicaciones_estatus_historial
                            WHERE co_estatus_aplicacion = 366
                        ),
                        ventas_por_mes AS (
                            SELECT 
                                ma.anio,
                                ma.mes_inicio AS mes,
                                SUM(a.nu_precio_total) AS monto_total_mensual,
                                COUNT(a.co_aplicacion) AS cantidad_aplicaciones,
                                STRING_AGG(DISTINCT o.tx_nombre, ', ') AS oficinas_involucradas,
                                STRING_AGG(a.co_aplicacion::TEXT, ', ') AS aplicaciones_involucradas
                            FROM c001t_aplicaciones a
                            JOIN aplicaciones_distintas ad ON a.co_aplicacion = ad.co_aplicacion
                            INNER JOIN usuarios_de_mis_oficinas um ON a.co_usuario = um.co_usuario
                            INNER JOIN c012t_usuarios_oficinas uo ON a.co_usuario = uo.co_usuario
                            INNER JOIN i008t_oficinas o ON uo.co_oficina = o.co_oficina
                            JOIN meses_anios ma ON ad.mes_asignado = ma.mes_inicio
                            WHERE a.co_estatus_aplicacion <> 372
                            GROUP BY ma.anio, ma.mes_inicio
                        )
                        SELECT 
                            TO_CHAR(vpm.mes, 'YYYY') AS anio,
                            TO_CHAR(vpm.mes, 'MM') AS numero_mes,
                            COALESCE(vpm.cantidad_aplicaciones, 0) AS cantidad_aplicaciones,
                            COALESCE(vpm.monto_total_mensual, 0) AS monto_total_mensual
                        FROM ventas_por_mes vpm
                        ORDER BY vpm.anio, vpm.mes";
                return DB::select($sql, ['co_usuario' => $co_usuario]);
            } else {
                $sql = "WITH oficina_usuarios AS (
                            -- Obtener los usuarios que pertenecen a la misma oficina que el usuario logueado
                            SELECT uo.co_usuario 
                            FROM c012t_usuarios_oficinas uo
                            WHERE uo.co_oficina = (SELECT co_oficina FROM c012t_usuarios_oficinas WHERE co_usuario = :co_usuario)
                        ),
                        aplicaciones_distintas AS (
                            -- Identificar aplicaciones únicas que hayan pasado por estatus 366 al menos una vez
                            SELECT DISTINCT ON (co_aplicacion) 
                                co_aplicacion, fe_activacion_estatus
                            FROM c026t_aplicaciones_estatus_historial
                            WHERE co_estatus_aplicacion = 366
                            ORDER BY co_aplicacion, fe_activacion_estatus
                        ),
                        meses_anios AS (
                            -- Generar los meses de los años actual y anterior
                            SELECT anio, mes
                            FROM (
                                SELECT EXTRACT(YEAR FROM CURRENT_DATE) AS anio, generate_series(1,12) AS mes
                                UNION ALL
                                SELECT EXTRACT(YEAR FROM CURRENT_DATE) - 1 AS anio, generate_series(1,12) AS mes
                            ) AS meses
                        )
                        SELECT 
                            ma.anio,
                            TO_CHAR(ma.mes, 'FM00') AS numero_mes,
                            COALESCE(COUNT(a.co_aplicacion), 0) AS cantidad_aplicaciones,
                            COALESCE(SUM(a.nu_precio_total), 0) AS monto_total_mensual
                        FROM meses_anios ma
                        LEFT JOIN aplicaciones_distintas ad 
                            ON ma.anio = EXTRACT(YEAR FROM ad.fe_activacion_estatus)
                            AND ma.mes = EXTRACT(MONTH FROM ad.fe_activacion_estatus)
                        LEFT JOIN c001t_aplicaciones a 
                            ON a.co_aplicacion = ad.co_aplicacion
                            AND a.co_usuario IN (SELECT co_usuario FROM oficina_usuarios)
                            AND ad.fe_activacion_estatus >= DATE_TRUNC('month', ad.fe_activacion_estatus) + INTERVAL '2 days'
                            AND ad.fe_activacion_estatus < DATE_TRUNC('month', ad.fe_activacion_estatus) + INTERVAL '1 month' + INTERVAL '2 days'
                            AND a.co_estatus_aplicacion <> 372
                        GROUP BY ma.anio, ma.mes
                        ORDER BY ma.anio, ma.mes";
                return DB::select($sql, ['co_usuario' => $co_usuario]);
            }
        }
        elseif ($vista === 'global') {
            $sql = "WITH aplicaciones_distintas AS (
                SELECT DISTINCT ON (co_aplicacion) 
                    co_aplicacion, fe_activacion_estatus
                FROM c026t_aplicaciones_estatus_historial
                WHERE co_estatus_aplicacion = 366
                ORDER BY co_aplicacion, fe_activacion_estatus
            ),
            meses_anios AS (
                SELECT anio, mes
                FROM (
                    SELECT EXTRACT(YEAR FROM CURRENT_DATE) AS anio, generate_series(1,12) AS mes
                    UNION ALL
                    SELECT EXTRACT(YEAR FROM CURRENT_DATE) - 1 AS anio, generate_series(1,12) AS mes
                ) AS meses
            )
            SELECT 
                ma.anio,
                TO_CHAR(ma.mes, 'FM00') AS numero_mes,
                COALESCE(COUNT(a.co_aplicacion), 0) AS cantidad_aplicaciones,
                COALESCE(SUM(a.nu_precio_total), 0) AS monto_total_mensual
            FROM meses_anios ma
            LEFT JOIN aplicaciones_distintas ad 
                ON ma.anio = EXTRACT(YEAR FROM ad.fe_activacion_estatus)
                AND ma.mes = EXTRACT(MONTH FROM ad.fe_activacion_estatus)
            LEFT JOIN c001t_aplicaciones a 
                ON a.co_aplicacion = ad.co_aplicacion
                AND ad.fe_activacion_estatus >= DATE_TRUNC('month', ad.fe_activacion_estatus) + INTERVAL '2 days'
                AND ad.fe_activacion_estatus < DATE_TRUNC('month', ad.fe_activacion_estatus) + INTERVAL '1 month' + INTERVAL '2 days'
                AND a.co_estatus_aplicacion <> 372
            GROUP BY ma.anio, ma.mes
            ORDER BY ma.anio, ma.mes";
            
            return DB::select($sql);
        }
        


    }

    /**
     * Obtener Top 10 del equipo
     */
    private function getTop10Equipo($co_usuario, $vista = 'equipo')
    {
        if ($vista === 'oficina') {
            $sql = "WITH oficina_usuarios AS (
                -- Obtener todos los usuarios que pertenecen a la misma oficina que el usuario logueado
                SELECT co_usuario 
                FROM c012t_usuarios_oficinas 
                WHERE co_oficina = (SELECT co_oficina FROM c012t_usuarios_oficinas WHERE co_usuario = :co_usuario)
            ),
            aplicaciones_distintas AS (
                -- Identificar aplicaciones únicas que hayan pasado por estatus 366 al menos una vez
                SELECT DISTINCT ON (co_aplicacion) 
                    co_aplicacion, fe_activacion_estatus
                FROM c026t_aplicaciones_estatus_historial
                WHERE co_estatus_aplicacion = 366
                ORDER BY co_aplicacion, fe_activacion_estatus
            )
            SELECT 
                u.tx_primer_nombre || ' ' || u.tx_primer_apellido AS nombre_usuario,
                a.co_usuario,
                COUNT(a.co_aplicacion) AS cantidad_aplicaciones,
                SUM(a.nu_precio_total) AS monto_total
            FROM c001t_aplicaciones a
            JOIN aplicaciones_distintas ad ON a.co_aplicacion = ad.co_aplicacion
            JOIN oficina_usuarios ou ON a.co_usuario = ou.co_usuario -- Solo usuarios de la oficina
            JOIN c002t_usuarios u ON a.co_usuario = u.co_usuario
            WHERE ad.fe_activacion_estatus >= DATE_TRUNC('month', CURRENT_DATE) + INTERVAL '2 days'
              AND ad.fe_activacion_estatus < DATE_TRUNC('month', CURRENT_DATE) + INTERVAL '1 month' + INTERVAL '2 days'
              AND a.co_estatus_aplicacion <> 372
            GROUP BY u.tx_primer_nombre, u.tx_primer_apellido, a.co_usuario
            ORDER BY monto_total DESC 
            LIMIT 10";
            
            return DB::select($sql, ['co_usuario' => $co_usuario]);
        } 
        elseif ($vista === 'equipo') {
            // Consulta original para vista de equipo
            $sql = "WITH RECURSIVE equipo AS (
                -- No incluir al usuario actual en el top, pero sí usarlo para encontrar su equipo
                SELECT co_usuario
                FROM c002t_usuarios
                WHERE co_usuario_padre = :co_usuario
                UNION ALL
                SELECT c.co_usuario
                FROM c002t_usuarios c
                INNER JOIN equipo e ON c.co_usuario_padre = e.co_usuario
            ),
            aplicaciones_distintas AS (
                SELECT DISTINCT ON (co_aplicacion) 
                    co_aplicacion, fe_activacion_estatus
                FROM c026t_aplicaciones_estatus_historial
                WHERE co_estatus_aplicacion = 366
                ORDER BY co_aplicacion, fe_activacion_estatus
            )
            SELECT 
                u.tx_primer_nombre || ' ' || u.tx_primer_apellido AS nombre_usuario,
                a.co_usuario,
                COUNT(a.co_aplicacion) AS cantidad_aplicaciones,
                SUM(a.nu_precio_total) AS monto_total
            FROM c001t_aplicaciones a
            JOIN aplicaciones_distintas ad ON a.co_aplicacion = ad.co_aplicacion
            JOIN equipo eq ON a.co_usuario = eq.co_usuario
            JOIN c002t_usuarios u ON a.co_usuario = u.co_usuario
            WHERE ad.fe_activacion_estatus >= DATE_TRUNC('month', CURRENT_DATE) + INTERVAL '2 days'
            AND ad.fe_activacion_estatus < DATE_TRUNC('month', CURRENT_DATE) + INTERVAL '1 month' + INTERVAL '2 days'
            AND a.co_estatus_aplicacion <> 372
            GROUP BY u.tx_primer_nombre, u.tx_primer_apellido, a.co_usuario
            ORDER BY monto_total DESC
            LIMIT 10";
            
            return DB::select($sql, ['co_usuario' => $co_usuario]);
        }
        elseif ($vista === 'personal') {
            $sql = "WITH aplicaciones_distintas AS (
                SELECT DISTINCT ON (co_aplicacion) 
                    co_aplicacion, fe_activacion_estatus
                FROM c026t_aplicaciones_estatus_historial
                WHERE co_estatus_aplicacion = 366
                ORDER BY co_aplicacion, fe_activacion_estatus
            ),
            equipo_padre AS (
                SELECT co_usuario 
                FROM c002t_usuarios
                WHERE co_usuario_padre = (
                    SELECT co_usuario_padre 
                    FROM c002t_usuarios 
                    WHERE co_usuario = :co_usuario
                )
                OR co_usuario = :co_usuario
            )
            SELECT 
                u.tx_primer_nombre || ' ' || u.tx_primer_apellido AS nombre_usuario,
                SUM(a.nu_precio_total) AS monto_total
            FROM c001t_aplicaciones a
            JOIN aplicaciones_distintas ad ON a.co_aplicacion = ad.co_aplicacion
            JOIN equipo_padre ep ON a.co_usuario = ep.co_usuario
            JOIN c002t_usuarios u ON a.co_usuario = u.co_usuario
            WHERE ad.fe_activacion_estatus >= DATE_TRUNC('month', CURRENT_DATE) + INTERVAL '2 days'
            AND ad.fe_activacion_estatus < DATE_TRUNC('month', CURRENT_DATE) + INTERVAL '1 month' + INTERVAL '2 days'
            AND a.co_estatus_aplicacion <> 372
            GROUP BY u.tx_primer_nombre, u.tx_primer_apellido
            ORDER BY monto_total DESC";
            return DB::select($sql, ['co_usuario' => $co_usuario]);
            
        }

    }

    /**
     * Obtener Daily Sales del mes actual
     */
    private function getDailySales($co_usuario, $vista = 'personal')
    {
        // Obtener el rol del usuario
        $rol_co = session('rol_userlogin_co');
        
        if ($vista === 'global') {
            $sql = "WITH rango_fechas AS (
                -- Generamos todas las fechas dentro del período establecido
                SELECT generate_series(
                    DATE_TRUNC('month', CURRENT_DATE) + INTERVAL '2 days',  -- Inicio: día 3 del mes actual
                    DATE_TRUNC('month', CURRENT_DATE) + INTERVAL '1 month' + INTERVAL '1 day',  -- Fin: día 2 del mes siguiente
                    INTERVAL '1 day'
                )::DATE AS fecha
            ),
            aplicaciones_distintas AS (
                -- Identificar aplicaciones únicas que hayan pasado por estatus 366 al menos una vez
                SELECT DISTINCT ON (co_aplicacion) 
                    co_aplicacion, fe_activacion_estatus
                FROM c026t_aplicaciones_estatus_historial
                WHERE co_estatus_aplicacion = 366
                ORDER BY co_aplicacion, fe_activacion_estatus
            ),
            ventas_dia AS (
                -- Obtener el volumen de ventas y número de aplicaciones por día
                SELECT 
                    DATE(ad.fe_activacion_estatus) AS fecha,
                    SUM(a.nu_precio_total) AS monto_total_ventas,
                    COUNT(a.co_aplicacion) AS cantidad_aplicaciones
                FROM c001t_aplicaciones a
                JOIN aplicaciones_distintas ad ON a.co_aplicacion = ad.co_aplicacion
                WHERE ad.fe_activacion_estatus >= DATE_TRUNC('month', CURRENT_DATE) + INTERVAL '2 days'
                AND ad.fe_activacion_estatus < DATE_TRUNC('month', CURRENT_DATE) + INTERVAL '1 month' + INTERVAL '2 days'
                AND a.co_estatus_aplicacion <> 372
                GROUP BY fecha
            )
            -- Unir la serie de fechas con los datos de ventas
            SELECT 
                rf.fecha as dia,
                EXTRACT(DAY FROM rf.fecha) AS dia_numero,
                COALESCE(vd.cantidad_aplicaciones, 0) AS cantidad,
                COALESCE(vd.monto_total_ventas, 0) AS monto
            FROM rango_fechas rf
            LEFT JOIN ventas_dia vd ON rf.fecha = vd.fecha
            ORDER BY rf.fecha";
            
            return DB::select($sql);
        } elseif ($vista === 'oficina') {
            // Si es director regional (rol 8), usar SQL específico para múltiples oficinas
            if ($rol_co == 8) {
                $sql = "WITH rango_fechas AS (
                    -- Generamos todas las fechas dentro del período establecido
                    SELECT generate_series(
                        DATE_TRUNC('month', CURRENT_DATE) + INTERVAL '2 days',
                        DATE_TRUNC('month', CURRENT_DATE) + INTERVAL '1 month' + INTERVAL '2 days',
                        INTERVAL '1 day'
                    )::DATE AS fecha
                ),
                mis_oficinas AS (
                    SELECT co_oficina 
                    FROM c018t_usr_ofic_reg 
                    WHERE co_usuario = :co_usuario
                ), 
                usuarios_de_mis_oficinas AS (
                    SELECT DISTINCT uo.co_usuario
                    FROM c012t_usuarios_oficinas uo
                    INNER JOIN mis_oficinas mo ON uo.co_oficina = mo.co_oficina
                ),
                aplicaciones_distintas AS (
                    -- Identificar aplicaciones únicas que hayan pasado por estatus 366 al menos una vez
                    SELECT DISTINCT ON (co_aplicacion) 
                        co_aplicacion, fe_activacion_estatus
                    FROM c026t_aplicaciones_estatus_historial
                    WHERE co_estatus_aplicacion = 366
                    ORDER BY co_aplicacion, fe_activacion_estatus
                ),
                ventas_dia AS (
                    -- Obtener el volumen de ventas y cantidad de aplicaciones por día
                    SELECT 
                        DATE(ad.fe_activacion_estatus) AS fecha,
                        COALESCE(SUM(a.nu_precio_total), 0) AS monto_total_ventas,
                        COALESCE(COUNT(a.co_aplicacion), 0) AS cantidad_aplicaciones
                    FROM c001t_aplicaciones a
                    JOIN aplicaciones_distintas ad ON a.co_aplicacion = ad.co_aplicacion
                    INNER JOIN usuarios_de_mis_oficinas um ON a.co_usuario = um.co_usuario
                    WHERE ad.fe_activacion_estatus >= DATE_TRUNC('month', CURRENT_DATE) + INTERVAL '2 days'
                    AND ad.fe_activacion_estatus < DATE_TRUNC('month', CURRENT_DATE) + INTERVAL '1 month' + INTERVAL '2 days'
                    AND a.co_estatus_aplicacion <> 372
                    GROUP BY fecha
                )
                -- Unir la serie de fechas con los datos de ventas de las oficinas
                SELECT 
                    rf.fecha,
                    EXTRACT(DAY FROM rf.fecha) AS dia_numero,
                    COALESCE(vd.monto_total_ventas, 0) AS monto,
                    COALESCE(vd.cantidad_aplicaciones, 0) AS cantidad
                FROM rango_fechas rf
                LEFT JOIN ventas_dia vd ON rf.fecha = vd.fecha
                ORDER BY rf.fecha";
                
                return DB::select($sql, ['co_usuario' => $co_usuario]);
            } else {
                $sql = "WITH rango_fechas AS (
        -- Generamos todas las fechas dentro del período establecido
        SELECT generate_series(
            DATE_TRUNC('month', CURRENT_DATE) + INTERVAL '2 days',
            DATE_TRUNC('month', CURRENT_DATE) + INTERVAL '1 month' + INTERVAL '2 days',
            INTERVAL '1 day'
        )::DATE AS fecha
    ),
    oficina_usuarios AS (
        -- Obtener los usuarios de la oficina y el nombre de la oficina
        SELECT uo.co_usuario, o.tx_nombre AS nombre_oficina
        FROM c012t_usuarios_oficinas uo
        JOIN i008t_oficinas o ON uo.co_oficina = o.co_oficina
        WHERE uo.co_oficina = (SELECT co_oficina FROM c012t_usuarios_oficinas WHERE co_usuario = :co_usuario) 
    ),
    aplicaciones_distintas AS (
        -- Identificar aplicaciones únicas que hayan pasado por estatus 366 al menos una vez
        SELECT DISTINCT ON (co_aplicacion) 
            co_aplicacion, fe_activacion_estatus
        FROM c026t_aplicaciones_estatus_historial
        WHERE co_estatus_aplicacion = 366
        ORDER BY co_aplicacion, fe_activacion_estatus
    ),
    ventas_dia AS (
        -- Obtener el volumen de ventas y cantidad de aplicaciones
        SELECT 
            DATE(ad.fe_activacion_estatus) AS fecha,
            COALESCE(SUM(a.nu_precio_total), 0) AS monto_total_ventas,
            COALESCE(COUNT(a.co_aplicacion), 0) AS cantidad_aplicaciones
        FROM c001t_aplicaciones a
        JOIN aplicaciones_distintas ad ON a.co_aplicacion = ad.co_aplicacion
        INNER JOIN oficina_usuarios ou ON a.co_usuario = ou.co_usuario
        WHERE ad.fe_activacion_estatus >= DATE_TRUNC('month', CURRENT_DATE) + INTERVAL '2 days'
        AND ad.fe_activacion_estatus < DATE_TRUNC('month', CURRENT_DATE) + INTERVAL '1 month' + INTERVAL '2 days'
        AND a.co_estatus_aplicacion <> 372
        GROUP BY fecha
    )
    -- Unir la serie de fechas con los datos de ventas de la oficina
    SELECT 
        rf.fecha,
        EXTRACT(DAY FROM rf.fecha) AS dia_numero,
        COALESCE(vd.monto_total_ventas, 0) AS monto,
        COALESCE(vd.cantidad_aplicaciones, 0) AS cantidad
    FROM rango_fechas rf
    LEFT JOIN ventas_dia vd ON rf.fecha = vd.fecha
    ORDER BY rf.fecha";
                
                return DB::select($sql, ['co_usuario' => $co_usuario]);
            }
        } elseif ($vista === 'personal') {
            $sql = "WITH aplicaciones_distintas AS (
                SELECT DISTINCT ON (co_aplicacion) 
                    co_aplicacion, fe_activacion_estatus
                FROM c026t_aplicaciones_estatus_historial
                WHERE co_estatus_aplicacion = 366
                ORDER BY co_aplicacion, fe_activacion_estatus
            ),
            dias AS (
                SELECT generate_series(:inicio_mes::date, :fin_mes::date, '1 day'::interval)::date AS dia
            )
            SELECT 
                d.dia,
                EXTRACT(DAY FROM d.dia) AS dia_numero,
                COALESCE(COUNT(a.co_aplicacion), 0) AS cantidad,
                COALESCE(SUM(a.nu_precio_total), 0) AS monto
            FROM dias d
            LEFT JOIN aplicaciones_distintas ad 
                ON DATE(ad.fe_activacion_estatus) = d.dia
            LEFT JOIN c001t_aplicaciones a 
                ON a.co_aplicacion = ad.co_aplicacion
                AND a.co_usuario = :co_usuario
                AND a.co_estatus_aplicacion <> 372
            GROUP BY d.dia
            ORDER BY d.dia";
            
            $inicioMes = Carbon::now()->startOfMonth()->addDays(2);
            $finMes = Carbon::now()->addMonth()->startOfMonth()->addDays(1);
            
            return DB::select(
                $sql, [
                'inicio_mes' => $inicioMes->format('Y-m-d'),
                'fin_mes' => $finMes->format('Y-m-d'),
                'co_usuario' => $co_usuario
                ]
            );
        } else {
            // Vista de equipo
            $sql = "WITH RECURSIVE equipo AS (
                -- Usuarios del equipo de trabajo, incluyendo al usuario logueado
                SELECT co_usuario FROM c002t_usuarios WHERE co_usuario = :co_usuario
                UNION ALL
                SELECT c.co_usuario FROM c002t_usuarios c 
                INNER JOIN equipo e ON c.co_usuario_padre = e.co_usuario
            ),
            aplicaciones_distintas AS (
                -- Identificar aplicaciones únicas que hayan pasado por estatus 366 en el mes actual
                SELECT DISTINCT ON (co_aplicacion) 
                    co_aplicacion, fe_activacion_estatus
                FROM c026t_aplicaciones_estatus_historial
                WHERE co_estatus_aplicacion = 366
                AND fe_activacion_estatus >= DATE_TRUNC('month', CURRENT_DATE) + INTERVAL '2 days' 
                AND fe_activacion_estatus < DATE_TRUNC('month', CURRENT_DATE) + INTERVAL '1 month' + INTERVAL '2 days'
                ORDER BY co_aplicacion, fe_activacion_estatus
            ),
            dias_mes_actual AS (
                -- Generar los días del mes actual basado en la regla de inicio y fin
                SELECT fecha::date 
                FROM generate_series(
                    DATE_TRUNC('month', CURRENT_DATE) + INTERVAL '2 days', 
                    DATE_TRUNC('month', CURRENT_DATE) + INTERVAL '1 month' + INTERVAL '2 days' - INTERVAL '1 day', 
                    INTERVAL '1 day'
                ) AS fecha
            )
            SELECT 
                da.fecha as dia,
                EXTRACT(DAY FROM da.fecha) AS dia_numero,
                COALESCE(COUNT(a.co_aplicacion), 0) AS cantidad,
                COALESCE(SUM(a.nu_precio_total), 0) AS monto
            FROM dias_mes_actual da
            LEFT JOIN aplicaciones_distintas ad ON da.fecha = ad.fe_activacion_estatus::date
            LEFT JOIN c001t_aplicaciones a 
                ON a.co_aplicacion = ad.co_aplicacion
                AND a.co_usuario IN (SELECT co_usuario FROM equipo)
                AND a.co_estatus_aplicacion <> 372
                AND a.co_usuario <> :co_usuario_exclude
            GROUP BY da.fecha
            ORDER BY da.fecha";
            
            return DB::select(
                $sql, [
                'co_usuario' => $co_usuario,
                'co_usuario_exclude' => $co_usuario
                ]
            );
        }
    }

    /**
     * Obtener Sales by Region (Ventas por país)
     */
    private function getSalesByRegion()
    {
        $inicioMes = Carbon::now()->startOfMonth()->addDays(2);
        $finMes = Carbon::now()->addMonth()->startOfMonth()->addDays(1);
        
        $sql = "WITH aplicaciones_distintas AS (
            SELECT DISTINCT ON (co_aplicacion) 
                co_aplicacion, fe_activacion_estatus
            FROM c026t_aplicaciones_estatus_historial
            WHERE co_estatus_aplicacion = 366
            ORDER BY co_aplicacion, fe_activacion_estatus
        )
        SELECT 
            COALESCE(p.tx_nombre, 'Sin País') AS pais,
            COUNT(a.co_aplicacion) AS cantidad,
            SUM(a.nu_precio_total) AS monto
        FROM c001t_aplicaciones a
        JOIN aplicaciones_distintas ad ON a.co_aplicacion = ad.co_aplicacion
        LEFT JOIN c002t_usuarios u ON a.co_usuario = u.co_usuario
        LEFT JOIN i003t_paises p ON u.co_pais = p.co_pais
        WHERE ad.fe_activacion_estatus >= :inicio_mes
        AND ad.fe_activacion_estatus < :fin_mes
        AND a.co_estatus_aplicacion <> 372
        GROUP BY p.tx_nombre
        ORDER BY monto DESC";
        
        return DB::select(
            $sql, [
            'inicio_mes' => $inicioMes->format('Y-m-d'),
            'fin_mes' => $finMes->format('Y-m-d')
            ]
        );
    }

    /**
     * Obtener Sales Volume by Offices
     */
    private function getSalesVolumeByOffices()
    {
        $inicioMes = Carbon::now()->startOfMonth()->addDays(2);
        $finMes = Carbon::now()->addMonth()->startOfMonth()->addDays(1);
        
        $sql = "WITH aplicaciones_distintas AS (
            SELECT DISTINCT ON (co_aplicacion) 
                co_aplicacion, fe_activacion_estatus
            FROM c026t_aplicaciones_estatus_historial
            WHERE co_estatus_aplicacion = 366
            ORDER BY co_aplicacion, fe_activacion_estatus
        )
        SELECT 
            COALESCE(o.tx_nombre, 'Sin Oficina') AS oficina,
            COUNT(a.co_aplicacion) AS cantidad,
            SUM(a.nu_precio_total) AS monto
        FROM c001t_aplicaciones a
        JOIN aplicaciones_distintas ad ON a.co_aplicacion = ad.co_aplicacion
        LEFT JOIN c002t_usuarios u ON a.co_usuario = u.co_usuario
        LEFT JOIN c012t_usuarios_oficinas uo ON u.co_usuario = uo.co_usuario
        LEFT JOIN i008t_oficinas o ON uo.co_oficina = o.co_oficina
        WHERE ad.fe_activacion_estatus >= :inicio_mes
        AND ad.fe_activacion_estatus < :fin_mes
        AND a.co_estatus_aplicacion <> 372
        GROUP BY o.tx_nombre
        ORDER BY monto DESC";
        
        return DB::select(
            $sql, [
            'inicio_mes' => $inicioMes->format('Y-m-d'),
            'fin_mes' => $finMes->format('Y-m-d')
            ]
        );
    }

    /**
     * Obtener Top 3 Offices
     */
    private function getTop3Offices(string $vista = 'global')
    {
        if ($vista === 'global') {
            $sql = "WITH aplicaciones_distintas AS (
                -- Identificar aplicaciones únicas que hayan pasado por estatus 366 al menos una vez
                SELECT DISTINCT ON (co_aplicacion) 
                    co_aplicacion, fe_activacion_estatus, co_usuario_log AS co_usuario
                FROM c026t_aplicaciones_estatus_historial
                WHERE co_estatus_aplicacion = 366
                ORDER BY co_aplicacion, fe_activacion_estatus
            )
            SELECT 
                COALESCE(ofc.tx_nombre, 'Sin Oficina definida') AS oficina,
                COUNT(a.co_aplicacion) AS cantidad_aplicaciones,
                SUM(a.nu_precio_total) AS monto_total_ventas
            FROM c001t_aplicaciones a
            JOIN aplicaciones_distintas ad ON a.co_aplicacion = ad.co_aplicacion
            LEFT JOIN c012t_usuarios_oficinas uo ON a.co_usuario = uo.co_usuario  -- Relacionamos usuario con oficina
            LEFT JOIN i008t_oficinas ofc ON uo.co_oficina = ofc.co_oficina  -- Obtenemos el nombre de la oficina
            WHERE ad.fe_activacion_estatus >= DATE_TRUNC('month', CURRENT_DATE) + INTERVAL '2 days'
            AND ad.fe_activacion_estatus < DATE_TRUNC('month', CURRENT_DATE) + INTERVAL '1 month' + INTERVAL '2 days'
            AND a.co_estatus_aplicacion <> 372
            GROUP BY ofc.tx_nombre
            ORDER BY cantidad_aplicaciones DESC LIMIT 5";
            
            return DB::select($sql);
        }

        $inicioMes = Carbon::now()->startOfMonth()->addDays(2);
        $finMes = Carbon::now()->addMonth()->startOfMonth()->addDays(1);
        
        $sql = "WITH aplicaciones_distintas AS (
            -- Identificar aplicaciones únicas que hayan pasado por estatus 366 al menos una vez
            SELECT DISTINCT ON (co_aplicacion) 
                co_aplicacion, fe_activacion_estatus, co_usuario_log AS co_usuario
            FROM c026t_aplicaciones_estatus_historial
            WHERE co_estatus_aplicacion = 366
            ORDER BY co_aplicacion, fe_activacion_estatus
        )
        SELECT 
            COALESCE(ofc.tx_nombre, 'Sin Oficina definida') AS oficina,
            COUNT(a.co_aplicacion) AS cantidad,
            SUM(a.nu_precio_total) AS monto
        FROM c001t_aplicaciones a
        JOIN aplicaciones_distintas ad ON a.co_aplicacion = ad.co_aplicacion
        LEFT JOIN c012t_usuarios_oficinas uo ON a.co_usuario = uo.co_usuario  -- Relacionamos usuario con oficina
        LEFT JOIN i008t_oficinas ofc ON uo.co_oficina = ofc.co_oficina  -- Obtenemos el nombre de la oficina
        WHERE ad.fe_activacion_estatus >= :inicio_mes
        AND ad.fe_activacion_estatus < :fin_mes
        AND a.co_estatus_aplicacion <> 372
        GROUP BY ofc.tx_nombre
        ORDER BY cantidad DESC
        LIMIT 5";
        
        return DB::select(
            $sql, [
            'inicio_mes' => $inicioMes->format('Y-m-d'),
            'fin_mes' => $finMes->format('Y-m-d')
            ]
        );
    }

    /**
     * Obtener Top 5 Salespeople
     */
    private function getTop5Salespeople()
    {
        $inicioMes = Carbon::now()->startOfMonth()->addDays(2);
        $finMes = Carbon::now()->addMonth()->startOfMonth()->addDays(1);
        
        $sql = "WITH aplicaciones_distintas AS (
            SELECT DISTINCT ON (co_aplicacion) 
                co_aplicacion, fe_activacion_estatus
            FROM c026t_aplicaciones_estatus_historial
            WHERE co_estatus_aplicacion = 366
            ORDER BY co_aplicacion, fe_activacion_estatus
        )
        SELECT 
            u.tx_primer_nombre || ' ' || u.tx_primer_apellido AS vendedor,
            u.co_usuario,
            COUNT(a.co_aplicacion) AS cantidad,
            SUM(a.nu_precio_total) AS monto
        FROM c001t_aplicaciones a
        JOIN aplicaciones_distintas ad ON a.co_aplicacion = ad.co_aplicacion
        JOIN c002t_usuarios u ON a.co_usuario = u.co_usuario
        WHERE ad.fe_activacion_estatus >= :inicio_mes
        AND ad.fe_activacion_estatus < :fin_mes
        AND a.co_estatus_aplicacion <> 372
        GROUP BY u.tx_primer_nombre, u.tx_primer_apellido, u.co_usuario
        ORDER BY monto DESC
        LIMIT 5";
        
        return DB::select(
            $sql, [
            'inicio_mes' => $inicioMes->format('Y-m-d'),
            'fin_mes' => $finMes->format('Y-m-d')
            ]
        );
    }

    /**
     * Obtener Top 5 de toda la organización para el mes actual
     * 
     * @param  string $periodo
     * @return array
     */
    private function getTop5OrganizationSalespeople(string $periodo = 'anual'): array
    {
        // Determinar el filtro de fecha según el período
        if ($periodo === '90dias') {
            $dateFilter = "ad.fe_activacion_estatus >= CURRENT_DATE - INTERVAL '90 days'";
        } else {
            $dateFilter = "ad.fe_activacion_estatus >= DATE_TRUNC('month', CURRENT_DATE) + INTERVAL '2 days'
                           AND ad.fe_activacion_estatus < DATE_TRUNC('month', CURRENT_DATE) + INTERVAL '1 month' + INTERVAL '2 days'";
        }
        
        $sql = "WITH aplicaciones_distintas AS (
            -- Identificar aplicaciones únicas que hayan pasado por estatus 366 al menos una vez
            SELECT DISTINCT ON (co_aplicacion) 
                co_aplicacion, fe_activacion_estatus
            FROM c026t_aplicaciones_estatus_historial
            WHERE co_estatus_aplicacion = 366
            ORDER BY co_aplicacion, fe_activacion_estatus
        )
        SELECT 
            u.tx_primer_nombre || ' ' || u.tx_primer_apellido AS nombre_usuario,
            a.co_usuario,
            COUNT(a.co_aplicacion) AS cantidad_aplicaciones,
            SUM(a.nu_precio_total) AS monto_total
        FROM c001t_aplicaciones a
        JOIN aplicaciones_distintas ad ON a.co_aplicacion = ad.co_aplicacion
        JOIN c002t_usuarios u ON a.co_usuario = u.co_usuario
        WHERE {$dateFilter}
          AND a.co_estatus_aplicacion <> 372
        GROUP BY u.tx_primer_nombre, u.tx_primer_apellido, a.co_usuario
        ORDER BY monto_total DESC
        LIMIT 10";
        
        return DB::select($sql);
    }

    /**
     * Obtener Sales Volume by Roles
     */
    private function getSalesVolumeByRoles($periodo = 'anual')
    {
        // Determinar el filtro de fecha según el período
        if ($periodo === '90dias') {
            $dateFilter = "ad.fe_activacion_estatus >= CURRENT_DATE - INTERVAL '90 days'";
        } else {
            $dateFilter = "ad.fe_activacion_estatus >= DATE_TRUNC('year', CURRENT_DATE) + INTERVAL '2 days'
                           AND ad.fe_activacion_estatus < DATE_TRUNC('year', CURRENT_DATE) + INTERVAL '1 year' + INTERVAL '2 days'";
        }
        
        $sql = "WITH aplicaciones_distintas AS (
            -- Identificar aplicaciones únicas que hayan pasado por estatus 366 al menos una vez
            SELECT DISTINCT ON (co_aplicacion) 
                co_aplicacion, fe_activacion_estatus, co_usuario_log AS co_usuario
            FROM c026t_aplicaciones_estatus_historial
            WHERE co_estatus_aplicacion = 366
            ORDER BY co_aplicacion, fe_activacion_estatus
        )
        SELECT 
            COALESCE(r.tx_nombre, 'Sin Rol definido') AS grupo_rol,
            COUNT(a.co_aplicacion) AS cantidad,
            SUM(a.nu_precio_total) AS monto
        FROM c001t_aplicaciones a
        JOIN aplicaciones_distintas ad ON a.co_aplicacion = ad.co_aplicacion
        LEFT JOIN c014t_usuarios_roles ur ON a.co_usuario = ur.co_usuario
        LEFT JOIN i007t_roles r ON ur.co_rol = r.co_rol
        WHERE {$dateFilter}
        AND a.co_estatus_aplicacion <> 372
        GROUP BY r.tx_nombre
        ORDER BY monto DESC";
        
        return DB::select($sql);
    }

    /**
     * Obtener ventas por oficina
     * 
     * @return array
     */
    private function SalesOffice(): array
    {
        $inicioMes = Carbon::now()->startOfMonth()->addDays(2);
        $finMes = Carbon::now()->addMonth()->startOfMonth()->addDays(1);
        
        $sql = "WITH aplicaciones_distintas AS (
            -- Identificar aplicaciones únicas que hayan pasado por estatus 366 al menos una vez
            SELECT DISTINCT ON (co_aplicacion) 
                co_aplicacion, fe_activacion_estatus, co_usuario_log AS co_usuario
            FROM c026t_aplicaciones_estatus_historial
            WHERE co_estatus_aplicacion = 366
            ORDER BY co_aplicacion, fe_activacion_estatus
        )
        SELECT 
            COALESCE(ofc.tx_nombre, 'Sin Oficina definida') AS oficina,
            COUNT(a.co_aplicacion) AS cantidad_aplicaciones,
            SUM(a.nu_precio_total) AS monto_total_ventas
        FROM c001t_aplicaciones a
        JOIN aplicaciones_distintas ad ON a.co_aplicacion = ad.co_aplicacion
        LEFT JOIN c012t_usuarios_oficinas uo ON a.co_usuario = uo.co_usuario  -- Relacionamos usuario con oficina
        LEFT JOIN i008t_oficinas ofc ON uo.co_oficina = ofc.co_oficina  -- Obtenemos el nombre de la oficina
        WHERE ad.fe_activacion_estatus >= :inicio_mes
        AND ad.fe_activacion_estatus < :fin_mes
        AND a.co_estatus_aplicacion <> 372
        GROUP BY ofc.tx_nombre
        ORDER BY cantidad_aplicaciones DESC";
        
        return DB::select(
            $sql, [
            'inicio_mes' => $inicioMes->format('Y-m-d'),
            'fin_mes' => $finMes->format('Y-m-d')
            ]
        );
    }

    /**
     * Obtener ventas por región para la vista global
     * 
     * @return array
     */
    private function SalesByRegionGlobal(): array
    {
        $sql = "WITH aplicaciones_distintas AS (
            -- Identificar aplicaciones únicas que hayan pasado por estatus 366 al menos una vez
            SELECT DISTINCT ON (co_aplicacion) 
                co_aplicacion, fe_activacion_estatus, co_usuario_log AS co_usuario
            FROM c026t_aplicaciones_estatus_historial
            WHERE co_estatus_aplicacion = 366
            ORDER BY co_aplicacion, fe_activacion_estatus
        )
        SELECT 
            COALESCE(p.tx_nombre, 'Sin País definido') AS pais,
            COUNT(a.co_aplicacion) AS cantidad_aplicaciones,
            SUM(a.nu_precio_total) AS monto_total_ventas,
            STRING_AGG(DISTINCT ofc.tx_nombre, ', ') AS oficinas_involucradas
        FROM c001t_aplicaciones a
        JOIN aplicaciones_distintas ad ON a.co_aplicacion = ad.co_aplicacion
        LEFT JOIN c012t_usuarios_oficinas uo ON a.co_usuario = uo.co_usuario  -- Relacionamos usuario con oficina
        LEFT JOIN i008t_oficinas ofc ON uo.co_oficina = ofc.co_oficina  -- Obtenemos el nombre de la oficina
        LEFT JOIN i017t_paises p ON ofc.co_pais = p.co_pais  -- Relacionamos oficinas con países
        WHERE ad.fe_activacion_estatus >= DATE_TRUNC('month', CURRENT_DATE) + INTERVAL '2 days'
        AND ad.fe_activacion_estatus < DATE_TRUNC('month', CURRENT_DATE) + INTERVAL '1 month' + INTERVAL '2 days'
        AND a.co_estatus_aplicacion <> 372
        GROUP BY p.tx_nombre
        ORDER BY cantidad_aplicaciones DESC";
        
        return DB::select($sql);
    }

    /**
     * Get dashboard data for AJAX requests
     * 
     * @param  Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getDashboardData(Request $request)
    {
        try {
            $co_usuario = Auth()->id();
            $vista = $request->input('vista', 'personal');
            $periodo = $request->input('periodo', 'anual'); // anual o 90dias
            $periodoSalesVolumeByRoles = $request->input('periodoSalesVolumeByRoles', 'anual'); // período específico para Sales Volume by Roles
            $periodoOrdersByStatusGlobal = $request->input('periodoOrdersByStatusGlobal', 'anual'); // período específico para Orders by Status en vista global
            $periodoTop5OrganizationSalespeople = $request->input('periodoTop5OrganizationSalespeople', 'anual'); // período específico para Top 5 Organization Salespeople
            $rol_co = session('rol_userlogin_co');
            
            // Determinar qué período usar para Orders by Status según la vista
            $periodoOrdersByStatus = $vista === 'global' ? $periodoOrdersByStatusGlobal : $periodo;
            
            $data = [
                'totalRevenue' => $this->getTotalRevenue($co_usuario, $vista),
                'totalOrders' => $this->getTotalOrders($co_usuario, $vista),
                'ordersByStatus' => $this->getOrdersByStatus($co_usuario, $vista, $periodoOrdersByStatus),
                'volumenVentas' => $this->getVolumenVentasComparativo($co_usuario, $vista),
                'top10OfficeSalespeople' => $this->getTop10OfficeSalespeople($co_usuario, $vista),
            ];

            // Incluir datos adicionales según la vista
            if ($vista === 'personal') {
                $data['personal'] = [
                    'totalRevenue' => $this->getTotalRevenue($co_usuario, 'personal'),
                    'totalOrders' => $this->getTotalOrders($co_usuario, 'personal'),
                    'volumenVentas' => $this->getVolumenVentasComparativo($co_usuario, 'personal'),
                    'getTop10Equipo' => $this->getTop10Equipo($co_usuario, 'personal')
                ];
            } elseif ($vista === 'equipo') {
                $top10TeamVendors = $this->getTop10TeamVendors($co_usuario, 'equipo');
                Log::info('Top 10 Team Vendors Data:', ['data' => $top10TeamVendors]);
                
                $data['equipo'] = [
                    'totalRevenue' => $this->getTotalRevenue($co_usuario, 'equipo'),
                    'totalOrders' => $this->getTotalOrders($co_usuario, 'equipo'),
                    'volumenVentas' => $this->getVolumenVentasComparativo($co_usuario, 'equipo'),
                    'getTop10TeamMembers' => $this->getTop10TeamMembers($co_usuario, 'equipo'),
                    'getTop10TeamDirectorManager' => $this->getTop10TeamDirectorManager($co_usuario, 'equipo'),
                    'getTop10TeamVendors' => $top10TeamVendors
                ];
            } elseif ($vista === 'oficina') {
                $data['oficina'] = [
                    'totalRevenue' => $this->getTotalRevenue($co_usuario, 'oficina'),
                    'totalOrders' => $this->getTotalOrders($co_usuario, 'oficina'),
                    'volumenVentas' => $this->getVolumenVentasComparativo($co_usuario, 'oficina'),
                    'getTop10TeamDirectorManager' => $this->getTop10TeamDirectorManager($co_usuario, 'oficina')
                ];
                
                // Agregar gráficas adicionales solo para rol 8 (Director Regional)
                if ($rol_co == 8) {
                    $data['oficina']['getTop10MisOficinas'] = $this->getTop10MisOficinas($co_usuario);
                    $data['oficina']['getTop10OfficeSalespeople'] = $this->getTop10OfficeSalespeople($co_usuario, 'oficina');
                }
            }
            elseif ($vista === 'global') {
                $data['global'] = [
                    'totalRevenue' => $this->getTotalRevenue($co_usuario, 'global'),
                    'totalOrders' => $this->getTotalOrders($co_usuario, 'global'),
                    'volumenVentas' => $this->getVolumenVentasComparativo($co_usuario, 'global'),
                    'getSalesVolumeByRoles' => $this->getSalesVolumeByRoles($periodoSalesVolumeByRoles),
                    'getTop10TeamMembers' => $this->getTop10TeamMembers($co_usuario, 'global'),
                    'SalesOffice' => $this->SalesOffice($co_usuario, 'global'),
                    'getTop3Offices' => $this->getTop3Offices($co_usuario, 'global'),
                    'getTop5OrganizationSalespeople' => $this->getTop5OrganizationSalespeople($periodoTop5OrganizationSalespeople),
                    'SalesByRegionGlobal' => $this->SalesByRegionGlobal()
                ];
            }

            // Solo para roles que pueden ver ventas diarias
            if (in_array($rol_co, [3, 4, 5, 6, 7, 8, 9, 10, 11])) {
                $data['dailySales'] = $this->getDailySales($co_usuario, $vista);
            }

            // Agregar gráfica de ventas por oficina para directores regionales
            if ($rol_co === 4) { // Asumiendo que 4 es el ID del rol de director regional
                $data['salesByOffice'] = $this->getSalesByOfficeForRegionalDirector($co_usuario);
            }
            
            return response()->json($data);
        } catch (\Exception $e) {
            Log::error('Error en getDashboardData: ' . $e->getMessage(), [
                'exception' => $e,
                'user_id' => Auth()->id(),
                'vista' => $request->input('vista'),
                'periodo' => $request->input('periodo')
            ]);
            
            return response()->json([
                'error' => true,
                'message' => 'Error al cargar los datos del dashboard',
                'details' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }

    /**
     * Get top 10 team members based on view type
     */
    private function getTop10TeamMembers(int $co_usuario, string $vista = 'personal'): array
    {
        if ($vista === 'global') {
            $sql = "WITH aplicaciones_distintas AS (
    -- Identificar aplicaciones únicas que hayan pasado por estatus 366 al menos una vez
    SELECT DISTINCT ON (co_aplicacion) 
        co_aplicacion, fe_activacion_estatus
    FROM c026t_aplicaciones_estatus_historial
    WHERE co_estatus_aplicacion = 366
    ORDER BY co_aplicacion, fe_activacion_estatus
)
SELECT 
    u.tx_primer_nombre || ' ' || u.tx_primer_apellido || ' ($' || SUM(a.nu_precio_total) || ') ' AS name,
   -- a.co_usuario,
    COUNT(a.co_aplicacion) AS value --,     SUM(a.nu_precio_total) AS monto_total
FROM c001t_aplicaciones a
JOIN aplicaciones_distintas ad ON a.co_aplicacion = ad.co_aplicacion
JOIN c002t_usuarios u ON a.co_usuario = u.co_usuario
WHERE ad.fe_activacion_estatus >= DATE_TRUNC('month', CURRENT_DATE) + INTERVAL '2 days'
  AND ad.fe_activacion_estatus < DATE_TRUNC('month', CURRENT_DATE) + INTERVAL '1 month' + INTERVAL '2 days'
  AND a.co_estatus_aplicacion <> 372
GROUP BY u.tx_primer_nombre, u.tx_primer_apellido -- , a.co_usuario
ORDER BY 2  DESC
LIMIT 10;";
            
            return DB::select($sql);
        } elseif ($vista === 'oficina') {
            $sql = "WITH oficina_usuarios AS (
    -- Obtener la oficina del usuario y sus compañeros de oficina
    SELECT uo.co_usuario 
    FROM c012t_usuarios_oficinas uo
    WHERE uo.co_oficina = (SELECT co_oficina FROM c012t_usuarios_oficinas WHERE co_usuario = 68)
), 
	aplicaciones_distintas AS (
    -- Identificar aplicaciones únicas que hayan pasado por estatus 366 al menos una vez
    SELECT DISTINCT ON (co_aplicacion) 
        co_aplicacion, fe_activacion_estatus
    FROM c026t_aplicaciones_estatus_historial
    WHERE co_estatus_aplicacion = 366
    ORDER BY co_aplicacion, fe_activacion_estatus
)
SELECT 
    u.tx_primer_nombre || ' ' || u.tx_primer_apellido || '  ($' || SUM(a.nu_precio_total) || ') ' AS name,
    COUNT(a.co_aplicacion) AS value --,     SUM(a.nu_precio_total) AS monto_total
FROM c001t_aplicaciones a
JOIN aplicaciones_distintas ad ON a.co_aplicacion = ad.co_aplicacion
left JOIN c002t_usuarios u ON a.co_usuario = u.co_usuario
INNER JOIN oficina_usuarios ou ON a.co_usuario = ou.co_usuario  -- Incorporamos la lógica de oficina
WHERE ad.fe_activacion_estatus >= DATE_TRUNC('month', CURRENT_DATE) + INTERVAL '2 days'
  AND ad.fe_activacion_estatus  < DATE_TRUNC('month', CURRENT_DATE) + INTERVAL '1 month' + INTERVAL '2 days'
  AND a.co_estatus_aplicacion <> 372
GROUP BY  u.tx_primer_nombre, u.tx_primer_apellido -- , a.co_usuario
ORDER BY 2  DESC
LIMIT 10";
            
            return DB::select($sql, ['co_usuario' => $co_usuario]);
        } elseif ($vista === 'equipo') {
            $sql = "WITH RECURSIVE equipo AS (
                -- Usuarios del equipo de trabajo
                SELECT co_usuario FROM c002t_usuarios WHERE co_usuario = :co_usuario
                UNION ALL
                SELECT c.co_usuario FROM c002t_usuarios c 
                INNER JOIN equipo e ON c.co_usuario_padre = e.co_usuario
            ),
            aplicaciones_distintas AS (
                -- Identificar aplicaciones únicas que hayan pasado por estatus 366 al menos una vez
                SELECT DISTINCT ON (co_aplicacion) 
                    co_aplicacion, fe_activacion_estatus
                FROM c026t_aplicaciones_estatus_historial
                WHERE co_estatus_aplicacion = 366
                ORDER BY co_aplicacion, fe_activacion_estatus
            )
            SELECT 
                u.tx_primer_nombre || ' ' || u.tx_primer_apellido || ' ($' || SUM(a.nu_precio_total) || ') ' AS name,
                COUNT(a.co_aplicacion) AS value
            FROM c001t_aplicaciones a
            JOIN aplicaciones_distintas ad ON a.co_aplicacion = ad.co_aplicacion
            LEFT JOIN c002t_usuarios u ON a.co_usuario = u.co_usuario
            INNER JOIN equipo eq ON a.co_usuario = eq.co_usuario
            WHERE ad.fe_activacion_estatus >= DATE_TRUNC('month', CURRENT_DATE) + INTERVAL '2 days'
            AND ad.fe_activacion_estatus < DATE_TRUNC('month', CURRENT_DATE) + INTERVAL '1 month' + INTERVAL '2 days'
            AND a.co_estatus_aplicacion <> 372
            AND a.co_usuario <> :co_usuario_exclude
            GROUP BY u.tx_primer_nombre, u.tx_primer_apellido
            ORDER BY value DESC
            LIMIT 10";
            
            return DB::select(
                $sql, [
                'co_usuario' => $co_usuario,
                'co_usuario_exclude' => $co_usuario
                ]
            );
        } else {
            $sql = "WITH RECURSIVE equipo AS (
    -- Usuarios del equipo de trabajo
    SELECT co_usuario FROM c002t_usuarios WHERE co_usuario = 7
    UNION ALL
    SELECT c.co_usuario FROM c002t_usuarios c 
    INNER JOIN equipo e ON c.co_usuario_padre = e.co_usuario
)
,  aplicaciones_distintas AS (
    -- Identificar aplicaciones únicas que hayan pasado por estatus 366 al menos una vez
    SELECT DISTINCT ON (co_aplicacion) 
        co_aplicacion, fe_activacion_estatus
    FROM c026t_aplicaciones_estatus_historial
    WHERE co_estatus_aplicacion = 366
    ORDER BY co_aplicacion, fe_activacion_estatus
)
SELECT 
   u.tx_primer_nombre || ' ' || u.tx_primer_apellido || ' ($' || SUM(a.nu_precio_total) || ') ' AS name, COUNT(a.co_aplicacion) AS value  
FROM c001t_aplicaciones a 
JOIN aplicaciones_distintas ad ON a.co_aplicacion = ad.co_aplicacion 
left JOIN c002t_usuarios u ON a.co_usuario = u.co_usuario 
INNER JOIN equipo ou ON a.co_usuario = ou.co_usuario  -- Incorporamos la lógica de oficina 
WHERE ad.fe_activacion_estatus >= DATE_TRUNC('month', CURRENT_DATE) + INTERVAL '2 days' 
  AND ad.fe_activacion_estatus  < DATE_TRUNC('month', CURRENT_DATE) + INTERVAL '1 month' + INTERVAL '2 days' 
  AND a.co_estatus_aplicacion <> 372 
GROUP BY  u.tx_primer_nombre, u.tx_primer_apellido   
ORDER BY 2  DESC 
LIMIT 10";

            return DB::select($sql);
        }
    }

    /**
     * Obtener Top 10 del equipo para vista de Director y Manager
     * 
     * @param  int    $co_usuario
     * @param  string $vista
     * @return array
     */
    private function getTop10TeamDirectorManager(int $co_usuario, string $vista = 'equipo'): array
    {
        // Obtener el rol del usuario
        $rol_co = session('rol_userlogin_co');
        
        if ($vista === 'equipo') {
            $sql = "WITH RECURSIVE equipo AS (
                -- Usuarios del equipo de trabajo
                SELECT co_usuario FROM c002t_usuarios WHERE co_usuario = :co_usuario
                UNION ALL
                SELECT c.co_usuario FROM c002t_usuarios c 
                INNER JOIN equipo e ON c.co_usuario_padre = e.co_usuario
            ),
            aplicaciones_distintas AS (
                -- Identificar aplicaciones únicas que hayan pasado por estatus 366 al menos una vez
                SELECT DISTINCT ON (co_aplicacion) 
                    co_aplicacion, fe_activacion_estatus
                FROM c026t_aplicaciones_estatus_historial
                WHERE co_estatus_aplicacion = 366
                ORDER BY co_aplicacion, fe_activacion_estatus
            )
            SELECT 
                u.tx_primer_nombre || ' ' || u.tx_primer_apellido || ' ($' || SUM(a.nu_precio_total) || ') ' AS name,
                COUNT(a.co_aplicacion) AS value
            FROM c001t_aplicaciones a
            JOIN aplicaciones_distintas ad ON a.co_aplicacion = ad.co_aplicacion
            LEFT JOIN c002t_usuarios u ON a.co_usuario = u.co_usuario
            INNER JOIN equipo ou ON a.co_usuario = ou.co_usuario
            WHERE ad.fe_activacion_estatus >= DATE_TRUNC('month', CURRENT_DATE) + INTERVAL '2 days'
            AND ad.fe_activacion_estatus < DATE_TRUNC('month', CURRENT_DATE) + INTERVAL '1 month' + INTERVAL '2 days'
            AND a.co_estatus_aplicacion <> 372
            GROUP BY u.tx_primer_nombre, u.tx_primer_apellido
            ORDER BY value DESC
            LIMIT 10";
            
            return DB::select($sql, ['co_usuario' => $co_usuario]);
        } elseif ($vista === 'oficina') {
            // Si es director regional (rol 8), usar SQL específico para múltiples oficinas
            if ($rol_co == 8) {
                $sql = "WITH mis_oficinas AS (
                    SELECT co_oficina 
                    FROM c018t_usr_ofic_reg 
                    WHERE co_usuario = :co_usuario
                ), 
                usuarios_de_mis_oficinas AS (
                    SELECT DISTINCT uo.co_usuario
                    FROM c012t_usuarios_oficinas uo
                    INNER JOIN mis_oficinas mo ON uo.co_oficina = mo.co_oficina
                ), 
                aplicaciones_distintas AS (
                    -- Identificar aplicaciones únicas que hayan pasado por estatus 366 al menos una vez
                    SELECT DISTINCT ON (co_aplicacion) 
                        co_aplicacion, fe_activacion_estatus
                    FROM c026t_aplicaciones_estatus_historial
                    WHERE co_estatus_aplicacion = 366
                    ORDER BY co_aplicacion, fe_activacion_estatus
                )
                SELECT 
                    u.tx_primer_nombre || ' ' || u.tx_primer_apellido || '  ($' || SUM(a.nu_precio_total) || ') ' AS name,
                    COUNT(a.co_aplicacion) AS value
                FROM c001t_aplicaciones a
                JOIN aplicaciones_distintas ad ON a.co_aplicacion = ad.co_aplicacion
                LEFT JOIN c002t_usuarios u ON a.co_usuario = u.co_usuario
                INNER JOIN usuarios_de_mis_oficinas um ON a.co_usuario = um.co_usuario
                WHERE ad.fe_activacion_estatus >= DATE_TRUNC('month', CURRENT_DATE) + INTERVAL '2 days'
                AND ad.fe_activacion_estatus < DATE_TRUNC('month', CURRENT_DATE) + INTERVAL '1 month' + INTERVAL '2 days'
                AND a.co_estatus_aplicacion <> 372
                GROUP BY u.tx_primer_nombre, u.tx_primer_apellido
                ORDER BY value DESC
                LIMIT 10";
                
                return DB::select($sql, ['co_usuario' => $co_usuario]);
            } else {
                $sql = "WITH oficina_usuarios AS (
                    -- Obtener la oficina del usuario y sus compañeros de oficina
                    SELECT uo.co_usuario 
                    FROM c012t_usuarios_oficinas uo
                    WHERE uo.co_oficina = (SELECT co_oficina FROM c012t_usuarios_oficinas WHERE co_usuario = :co_usuario)
                ), 
                aplicaciones_distintas AS (
                    -- Identificar aplicaciones únicas que hayan pasado por estatus 366 al menos una vez
                    SELECT DISTINCT ON (co_aplicacion) 
                        co_aplicacion, fe_activacion_estatus
                    FROM c026t_aplicaciones_estatus_historial
                    WHERE co_estatus_aplicacion = 366
                    ORDER BY co_aplicacion, fe_activacion_estatus
                )
                SELECT 
                    u.tx_primer_nombre || ' ' || u.tx_primer_apellido || '  ($' || SUM(a.nu_precio_total) || ') ' AS name,
                    COUNT(a.co_aplicacion) AS value
                FROM c001t_aplicaciones a
                JOIN aplicaciones_distintas ad ON a.co_aplicacion = ad.co_aplicacion
                LEFT JOIN c002t_usuarios u ON a.co_usuario = u.co_usuario
                INNER JOIN oficina_usuarios ou ON a.co_usuario = ou.co_usuario
                WHERE ad.fe_activacion_estatus >= DATE_TRUNC('month', CURRENT_DATE) + INTERVAL '2 days'
                AND ad.fe_activacion_estatus < DATE_TRUNC('month', CURRENT_DATE) + INTERVAL '1 month' + INTERVAL '2 days'
                AND a.co_estatus_aplicacion <> 372
                GROUP BY u.tx_primer_nombre, u.tx_primer_apellido
                ORDER BY value DESC
                LIMIT 10";
                
                return DB::select($sql, ['co_usuario' => $co_usuario]);
            }
        }
        
        return [];
    }

    private function getSalesByOfficeForRegionalDirector(int $co_usuario): array
    {
        $sql = "WITH RECURSIVE equipo AS (
            SELECT co_usuario FROM c002t_usuarios WHERE co_usuario = :co_usuario
            UNION ALL
            SELECT c.co_usuario FROM c002t_usuarios c 
            INNER JOIN equipo e ON c.co_usuario_padre = e.co_usuario
        ), 
        aplicaciones_distintas AS (
            SELECT DISTINCT ON (co_aplicacion) 
                co_aplicacion, fe_activacion_estatus
            FROM c026t_aplicaciones_estatus_historial
            WHERE co_estatus_aplicacion = 366
            ORDER BY co_aplicacion, fe_activacion_estatus
        ),
        ventas_por_oficina AS (
            SELECT 
                uo.co_oficina,
                SUM(a.nu_precio_total) AS monto_total_ventas,
                COUNT(a.co_aplicacion) AS total_aplicaciones_vendidas
            FROM c001t_aplicaciones a
            JOIN aplicaciones_distintas ad ON a.co_aplicacion = ad.co_aplicacion
            INNER JOIN equipo eq ON a.co_usuario = eq.co_usuario
            INNER JOIN c012t_usuarios_oficinas uo ON a.co_usuario = uo.co_usuario
            WHERE ad.fe_activacion_estatus >= DATE_TRUNC('month', CURRENT_DATE) + INTERVAL '2 days'
              AND ad.fe_activacion_estatus < DATE_TRUNC('month', CURRENT_DATE) + INTERVAL '1 month' + INTERVAL '2 days'
              AND a.co_estatus_aplicacion <> 372
            GROUP BY uo.co_oficina
        )
        SELECT 
            o.tx_nombre AS name,
            vpo.monto_total_ventas AS value,
            vpo.total_aplicaciones_vendidas AS total_orders
        FROM ventas_por_oficina vpo
        JOIN i008t_oficinas o ON vpo.co_oficina = o.co_oficina
        ORDER BY vpo.monto_total_ventas DESC";

        return DB::select($sql, ['co_usuario' => $co_usuario]);
    }

    private function getTop10TeamVendors(int $co_usuario, string $vista = 'equipo'): array
    {
        $sql = "WITH RECURSIVE equipo AS (
            -- Usuarios del equipo de trabajo
            SELECT co_usuario FROM c002t_usuarios WHERE co_usuario = :co_usuario
            UNION ALL
            SELECT c.co_usuario FROM c002t_usuarios c 
            INNER JOIN equipo e ON c.co_usuario_padre = e.co_usuario
        )
        , aplicaciones_distintas AS (
            -- Identificar aplicaciones únicas que hayan pasado por estatus 366 al menos una vez
            SELECT DISTINCT ON (co_aplicacion) 
                co_aplicacion, fe_activacion_estatus
            FROM c026t_aplicaciones_estatus_historial
            WHERE co_estatus_aplicacion = 366
            ORDER BY co_aplicacion, fe_activacion_estatus
        )
        SELECT 
            u.tx_primer_nombre || ' ' || u.tx_primer_apellido || ' ($' || SUM(a.nu_precio_total) || ') ' AS name, 
            COUNT(a.co_aplicacion) AS value  
        FROM c001t_aplicaciones a 
        JOIN aplicaciones_distintas ad ON a.co_aplicacion = ad.co_aplicacion 
        LEFT JOIN c002t_usuarios u ON a.co_usuario = u.co_usuario 
        INNER JOIN equipo ou ON a.co_usuario = ou.co_usuario
        WHERE ad.fe_activacion_estatus >= DATE_TRUNC('month', CURRENT_DATE) + INTERVAL '2 days' 
            AND ad.fe_activacion_estatus < DATE_TRUNC('month', CURRENT_DATE) + INTERVAL '1 month' + INTERVAL '2 days' 
            AND a.co_estatus_aplicacion <> 372 
            AND a.co_usuario <> :co_usuario_exclude
        GROUP BY u.tx_primer_nombre, u.tx_primer_apellido   
        ORDER BY value DESC 
        LIMIT 10";

        return DB::select($sql, [
            'co_usuario' => $co_usuario,
            'co_usuario_exclude' => $co_usuario
        ]);
    }

    private function getTop10OfficeSalespeople(int $co_usuario, string $vista = 'oficina'): array
    {
        // Obtener el rol del usuario
        $rol_co = session('rol_userlogin_co');
        
        // Si es director regional (rol 8), usar SQL específico para múltiples oficinas
        if ($rol_co == 8) {
            $query = "
                WITH mis_oficinas AS (
                    SELECT co_oficina 
                    FROM c018t_usr_ofic_reg 
                    WHERE co_usuario = :co_usuario
                ), 
                usuarios_de_mis_oficinas AS (
                    SELECT DISTINCT uo.co_usuario
                    FROM c012t_usuarios_oficinas uo
                    INNER JOIN mis_oficinas mo ON uo.co_oficina = mo.co_oficina
                ), 
                aplicaciones_distintas AS (
                    SELECT DISTINCT ON (co_aplicacion) 
                        co_aplicacion, fe_activacion_estatus
                    FROM c026t_aplicaciones_estatus_historial
                    WHERE co_estatus_aplicacion = 366
                    ORDER BY co_aplicacion, fe_activacion_estatus
                )
                SELECT 
                    u.tx_primer_nombre || ' ' || u.tx_primer_apellido || '  ($' || SUM(a.nu_precio_total) || ') ' AS name,
                    COUNT(a.co_aplicacion) AS value
                FROM c001t_aplicaciones a
                JOIN aplicaciones_distintas ad ON a.co_aplicacion = ad.co_aplicacion
                LEFT JOIN c002t_usuarios u ON a.co_usuario = u.co_usuario
                INNER JOIN usuarios_de_mis_oficinas um ON a.co_usuario = um.co_usuario
                WHERE ad.fe_activacion_estatus >= DATE_TRUNC('month', CURRENT_DATE) + INTERVAL '2 days'
                  AND ad.fe_activacion_estatus < DATE_TRUNC('month', CURRENT_DATE) + INTERVAL '1 month' + INTERVAL '2 days'
                  AND a.co_estatus_aplicacion <> 372
                GROUP BY u.tx_primer_nombre, u.tx_primer_apellido
                ORDER BY value DESC
                LIMIT 10";
        } else {
            $query = "
                WITH oficina_usuarios AS (
                    SELECT uo.co_usuario 
                    FROM c012t_usuarios_oficinas uo
                    WHERE uo.co_oficina = (SELECT co_oficina FROM c012t_usuarios_oficinas WHERE co_usuario = :co_usuario)
                ), 
                aplicaciones_distintas AS (
                    SELECT DISTINCT ON (co_aplicacion) 
                        co_aplicacion, fe_activacion_estatus
                    FROM c026t_aplicaciones_estatus_historial
                    WHERE co_estatus_aplicacion = 366
                    ORDER BY co_aplicacion, fe_activacion_estatus
                )
                SELECT 
                    u.tx_primer_nombre || ' ' || u.tx_primer_apellido || '  ($' || SUM(a.nu_precio_total) || ') ' AS name,
                    COUNT(a.co_aplicacion) AS value
                FROM c001t_aplicaciones a
                JOIN aplicaciones_distintas ad ON a.co_aplicacion = ad.co_aplicacion
                LEFT JOIN c002t_usuarios u ON a.co_usuario = u.co_usuario
                INNER JOIN oficina_usuarios ou ON a.co_usuario = ou.co_usuario
                WHERE ad.fe_activacion_estatus >= DATE_TRUNC('month', CURRENT_DATE) + INTERVAL '2 days'
                  AND ad.fe_activacion_estatus < DATE_TRUNC('month', CURRENT_DATE) + INTERVAL '1 month' + INTERVAL '2 days'
                  AND a.co_estatus_aplicacion <> 372
                GROUP BY u.tx_primer_nombre, u.tx_primer_apellido
                ORDER BY value DESC
                LIMIT 10";
        }

        $result = DB::select($query, ['co_usuario' => $co_usuario]);

        return array_map(function($item) {
            return [
                'name' => $item->name,
                'value' => (int)$item->value
            ];
        }, $result);
    }

    /**
     * Obtener Top 10 de Mis Oficinas para Director Regional (rol 8)
     * 
     * @param  int $co_usuario
     * @return array
     */
    private function getTop10MisOficinas(int $co_usuario): array
    {
        $sql = "WITH mis_oficinas AS (
            SELECT co_oficina 
            FROM c018t_usr_ofic_reg 
            WHERE co_usuario = :co_usuario
        ), 
        usuarios_de_mis_oficinas AS (
            SELECT DISTINCT uo.co_usuario, uo.co_oficina
            FROM c012t_usuarios_oficinas uo
            INNER JOIN mis_oficinas mo ON uo.co_oficina = mo.co_oficina
        ), 
        aplicaciones_distintas AS (
            SELECT DISTINCT ON (co_aplicacion) 
                co_aplicacion, fe_activacion_estatus
            FROM c026t_aplicaciones_estatus_historial
            WHERE co_estatus_aplicacion = 366
            ORDER BY co_aplicacion, fe_activacion_estatus
        ),
        ventas_por_oficina AS (
            SELECT 
                o.tx_nombre AS nombre_oficina,
                COUNT(a.co_aplicacion) AS cantidad_aplicaciones,
                SUM(a.nu_precio_total) AS monto_total
            FROM c001t_aplicaciones a
            JOIN aplicaciones_distintas ad ON a.co_aplicacion = ad.co_aplicacion
            JOIN c002t_usuarios u ON a.co_usuario = u.co_usuario
            JOIN usuarios_de_mis_oficinas um ON a.co_usuario = um.co_usuario
            JOIN i008t_oficinas o ON um.co_oficina = o.co_oficina
            WHERE ad.fe_activacion_estatus >= DATE_TRUNC('month', CURRENT_DATE) + INTERVAL '2 days'
              AND ad.fe_activacion_estatus <  DATE_TRUNC('month', CURRENT_DATE) + INTERVAL '1 month' + INTERVAL '2 days'
              AND a.co_estatus_aplicacion <> 372
            GROUP BY o.tx_nombre
        )
        SELECT 
            nombre_oficina,
            cantidad_aplicaciones,
            monto_total
        FROM ventas_por_oficina
        ORDER BY cantidad_aplicaciones DESC, monto_total DESC
        LIMIT 10";

        $result = DB::select($sql, ['co_usuario' => $co_usuario]);

        return array_map(function($item) {
            return [
                'name' => $item->nombre_oficina . ' ($' . number_format($item->monto_total, 2) . ')',
                'value' => (int)$item->cantidad_aplicaciones,
                'monto' => (float)$item->monto_total,
                'oficina' => $item->nombre_oficina
            ];
        }, $result);
    }

    /**
     * Obtener órdenes de instalación para el instalador (AJAX)
     * 
     * @param  Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getInstallerOrders(Request $request)
    {
        try {
            $co_usuario = Auth()->id();
            $rol_co = session('rol_userlogin_co');
            
            // Verificar que sea un instalador
            if ($rol_co !== 20) {
                return response()->json([
                    'error' => true,
                    'message' => 'Acceso no autorizado'
                ], 403);
            }
            
            // Por ahora retornamos datos falsos
            // TODO: Implementar consulta real a la base de datos
            $data = $this->getInstallerData($co_usuario);
            
            return response()->json([
                'success' => true,
                'data' => $data
            ]);
        } catch (\Exception $e) {
            Log::error('Error en getInstallerOrders: ' . $e->getMessage(), [
                'exception' => $e,
                'user_id' => Auth()->id()
            ]);
            
            return response()->json([
                'error' => true,
                'message' => 'Error al cargar las órdenes'
            ], 500);
        }
    }
}
