<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Models\C036tUsuariosNotificacionHi;
use App\Http\Requests\NotificationRequest;
use App\Models\I021tTipoNotificacion;
class NotificationsController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        // Get unread notifications before marking them as read
        $unreadNotifications = DB::table('c036t_usuarios_notificacion_his')
            ->where('co_usuario', $user->id)
            ->where('bo_visto', false)
            ->pluck('co_usrnotificahis');

        // Mark notifications as read
        if ($unreadNotifications->count() > 0) {
            DB::table('c036t_usuarios_notificacion_his')
                ->whereIn('co_usrnotificahis', $unreadNotifications)
                ->update(['bo_visto' => true]);

            // Reset notification counter
            DB::table('c035t_usuarios_notificacion')
                ->where('co_usuario', $user->id)
                ->update([
                    'nu_notifica' => 0,
                    'bo_visto' => true,
                    'fe_ultima_act' => Carbon::now()
                ]);
        }

        $notifications = DB::table('c036t_usuarios_notificacion_his')
            ->where('co_usuario', $user->id)
            ->orderBy('co_usrnotificahis', 'desc')
            ->orderBy('fe_registro', 'desc')
            ->paginate(10)
            ->through(function ($notification) use ($unreadNotifications) {
                $notification->fe_registro = Carbon::parse($notification->fe_registro);
                $notification->highlight = $unreadNotifications->contains($notification->co_usrnotificahis);
                return $notification;   
            });

        if ($request->ajax()) {
            return view('dashboard.notifications.partials.notifications', ['notifications' => $notifications])->render();
        }

        return view('dashboard.notifications.index', ['notifications' => $notifications]);
    }


    public function preferences()
    {
        $user = Auth::user();

        // Get all notification types
        $notificationTypes = DB::table('i021t_tipo_notificacion')->get();

        // Get user's existing notification preferences
        $userPreferences = DB::table('c035t_usuarios_notificacion')
            ->where('co_usuario', $user->id)
            ->get()
            ->keyBy('co_tiponoti'); // Key by co_tiponoti for easy access

        return view('dashboard.notifications.preferences', [
            'notificationTypes' => $notificationTypes,
            'userPreferences' => $userPreferences,
        ]);
    }

    public function updatePreferences(Request $request)
    {
        $user = Auth::user();

        // Validate the request (e.g., ensure the notification types are valid)
        $request->validate([
            'notification_types' => 'array',
            'notification_types.*' => 'exists:i021t_tipo_notificacion,co_tiponoti', // Ensure each type exists
        ]);

        $notificationTypes = DB::table('i021t_tipo_notificacion')->get()->pluck('co_tiponoti')->toArray();

        // Loop through all possible notification types
        foreach ($notificationTypes as $notificationType) {
            $shouldVisualize = in_array($notificationType, $request->input('notification_types', []));

            // Check if a record already exists for this user and notification type
            $existingPreference = DB::table('c035t_usuarios_notificacion')
                ->where('co_usuario', $user->id)
                ->where('co_tiponoti', $notificationType)
                ->first();

            if ($existingPreference) {
                // Update existing record
                DB::table('c035t_usuarios_notificacion')
                    ->where('co_usrnotifica', $existingPreference->co_usrnotifica)
                    ->update(['bo_visualizar' => $shouldVisualize]);
            } else {
                // Create a new record
                DB::table('c035t_usuarios_notificacion')->insert([
                    'co_usuario' => $user->id,
                    'co_tiponoti' => $notificationType,
                    'bo_visualizar' => $shouldVisualize,
                    'nu_notifica' => 0,
                    'bo_visto' => true,
                    'fe_registro' => Carbon::now(),
                    'fe_ultima_act' => Carbon::now(),
                ]);
            }
        }
        return redirect('/notifications')->with('success', 'Preferencias de notificaciones actualizadas correctamente.');
    }


    public function getUnreadCount()
    {
        try {
            $user = Auth::user();

            $count = DB::table('c036t_usuarios_notificacion_his')
                ->where('co_usuario', $user->id)
                ->where('bo_visto', false)
                ->count();

            return response()->json([
                'count' => $count
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'count' => 0,
                'error' => 'Error al obtener el conteo de notificaciones'
            ], 500);
        }
    }

    public function getRecentNotifications()
    {
        try {
            $user = Auth::user();

            $notifications = DB::table('c036t_usuarios_notificacion_his')
                ->where('co_usuario', $user->id)
                ->where('bo_visto', false)
                ->orderBy('co_usrnotificahis', 'desc')
                ->orderBy('fe_registro', 'desc')
                ->limit(5)
                ->get()
                ->map(function ($notification) {
                    $notification->fe_registro = Carbon::parse($notification->fe_registro)->format('Y-m-d H:i:s');
                    return $notification;
                });

            return response()->json([
                'notifications' => $notifications
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'notifications' => [],
                'error' => 'Error al obtener las notificaciones recientes'
            ], 500);
        }
    }

    public function markAsRead($id)
    {
        $user = Auth::user();

        // Update the notification history record
        DB::table('c036t_usuarios_notificacion_his')
            ->where('co_usrnotificahis', $id)
            ->update(['bo_visto' => true]);

        // Get total unread count after marking this one as read
        $unreadCount = DB::table('c036t_usuarios_notificacion_his')
            ->where('co_usuario', $user->id)
            ->where('bo_visto', false)
            ->count();

        // Update all notification counters for this user
        DB::table('c035t_usuarios_notificacion')
            ->where('co_usuario', $user->id)
            ->update([
                'nu_notifica' => $unreadCount,
                'bo_visto' => $unreadCount === 0,
                'fe_ultima_act' => Carbon::now()
            ]);

        return redirect()->back()->with('success', 'Notificación marcada como leída');
    }

    public function markAllAsRead()
    {
        $user = Auth::user();

        // Mark all notifications as read in history
        DB::table('c036t_usuarios_notificacion_his')
            ->where('co_usuario', $user->id)
            ->where('bo_visto', false)
            ->update(['bo_visto' => true]);

        // Reset notification counter
        DB::table('c035t_usuarios_notificacion')
            ->where('co_usuario', $user->id)
            ->update([
                'nu_notifica' => 0,
                'bo_visto' => true,
                'fe_ultima_act' => Carbon::now()
            ]);

        return redirect()->back()->with('success', 'Todas las notificaciones han sido marcadas como leídas');
    }

    public function create(Request $request)
    {
        $tiposNotificacion = I021tTipoNotificacion::all();
        return view('dashboard.notifications.create', compact('tiposNotificacion'));
    }
    
    public function store(NotificationRequest $request)
    {
        $user = Auth::user();
        $validatedData = $request->validated();
        $validatedData['co_usuario_log'] = $user->id;
            
        try {
            
            return DB::transaction(function() use ($validatedData, $user) {
                    // Obtener todos los usuarios activos
                    $usuariosActivos = DB::table('c002t_usuarios')
                        ->where('co_estatus_usuario', 1)
                        ->get();
                    
                    // Variable para contar notificaciones creadas
                    $contadorNotificaciones = 0;
                    
                    // Crear una notificación para cada usuario activo
                    foreach ($usuariosActivos as $usuario) {
                        $notificacionData = $validatedData;
                        $notificacionData['co_usuario'] = $usuario->co_usuario; // Asignar el ID del usuario activo
                        $notificacionData['bo_visto'] = false;
                        
                        // Crear la notificación para este usuario
                        C036tUsuariosNotificacionHi::create($notificacionData);
                        
                        // Actualizar contador o realizar operaciones adicionales
                        $contadorNotificaciones++;
                        
                        // Actualizar el contador de notificaciones no leídas para este usuario
                        DB::table('c035t_usuarios_notificacion')
                            ->where('co_usuario', $usuario->co_usuario)
                            ->where('co_tiponoti', $validatedData['co_tiponoti'])
                            ->increment('nu_notifica', 1, [
                                'bo_visto' => false,
                                'fe_ultima_act' => now()
                            ]);
                    }
                    
                    // Mensaje de éxito con contador
                    return redirect()->route('notifications.create')
                        ->with('success', "Notificacion creada correctamente,dirigida a $contadorNotificaciones usuarios");
                });
                
            } catch (\Exception $e) {
                // Registrar el error para depuración
                //Log::info('Problema al crear notificaciones: ' . $e->getMessage());                
                return redirect()->back()
                    ->with('error', 'Error al crear las notificaciones: ' . $e->getMessage());
            }
        }
}