<?php

namespace App\Http\Controllers;

use App\Models\C007tAplicacionesChat;
use App\Models\C033tUsuariosChatAplicacione;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Log;
use App\Models\C036tUsuariosNotificacionHi;
use Illuminate\Support\Facades\DB; 

class ChatController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(){
        //return view('dashboard.chat.chat')->with('co_aplicacion', '4012');
    }
    
    public function send(Request $request)
    {
        try {
            $request->validate([
                'co_aplicacion' => 'required|integer',
                'tx_comentario' => 'required|string',
                'reply_to' => 'nullable|integer'
            ]);

            // Verificar autorización
            $usuarioAutorizado = C033tUsuariosChatAplicacione::where([
                'co_usuario' => Auth::id(),
                'co_aplicacion' => $request->co_aplicacion
            ])->exists();

            if (!$usuarioAutorizado) {
                return response()->json([
                    'success' => false,
                    'message' => 'No está autorizado para escribir en este chat'
                ], 403);
            }

            // Iniciar transacción
            DB::beginTransaction();
            try {
                $chat = new C007tAplicacionesChat();
                $chat->co_aplicacion = $request->co_aplicacion;
                $chat->tx_comentario = $request->tx_comentario;
                $chat->co_usuario = Auth::id();
                $chat->fe_comentario = Carbon::now();
                $chat->ho_comentario = Carbon::now()->toTimeString();

                // Agregar referencia al mensaje respondido si existe
                if ($request->has('reply_to')) {
                    $chat->reply_to = $request->reply_to;
                
                    // Obtener el mensaje original y su autor
                    $mensajeOriginal = C007tAplicacionesChat::with('user:id,name,surname')
                        ->where('co_chat_apl', $request->reply_to)
                        ->first();

                    if ($mensajeOriginal) {
                        // Crear la notificación
                        $notificacion = new C036tUsuariosNotificacionHi();
                        $notificacion->co_aplicacion = $request->co_aplicacion;
                        $notificacion->co_usuario = $mensajeOriginal->co_usuario; 
                        $notificacion->tx_info_general = 'Nueva respuesta ['. $request->co_aplicacion.'] ' . 
                        Auth::user()->name . ' ' . Auth::user()->surname .
                        ' ha interactuado con tu comentario. ¡Entra al chat para continuar!';
                        
                        $notificacion->co_tiponoti = 1;
                        $notificacion->bo_visto = 0;
                        $notificacion->save();
                    }
                }
                
                $chat->save();
                DB::commit();

                return response()->json([
                    'success' => true,
                    'message' => 'Mensaje enviado exitosamente',
                    'data' => $chat
                ]);

            } catch (\Exception $e) {
                // Si hay algún error, revertir la transacción
                DB::rollBack();
                Log::error('Error en transacción de Chat: ' . $e->getMessage());
                throw $e;
            }

        } catch (\Exception $e) {
            Log::error('Error en Chat: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error al enviar el mensaje',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    public function history(Request $request)
    {
        $co_usuario = Auth::id();
        $co_aplicacion = $request->co_aplicacion;
        // Verificamos si el usuario está autorizado
        $usuarioAutorizado = C033tUsuariosChatAplicacione::where([
            'co_usuario' => $co_usuario,
            'co_aplicacion' => $co_aplicacion
        ])->exists();
    
        if (!$usuarioAutorizado) {
            return response()->json([
                'success' => false,
                'message' => 'No está autorizado para ver este chat'
            ], 403);
        }
    
        $query = C007tAplicacionesChat::where('co_aplicacion', $co_aplicacion)
            ->with([
                'user:id,name,surname,image_path',
                'usuarioChatAplicacion' => function($query) use ($co_aplicacion) {
                $query->where('co_aplicacion', $co_aplicacion)
                      ->select('co_usuario', 'co_aplicacion', 'tx_perfil');
                },
                'repliedMessage' => function($query) {
                $query->with([
                    'user:id,name,surname',
                    'usuarioChatAplicacion:co_usuario,co_aplicacion,tx_perfil'
                ]);
            }
        ]);

        if ($request->has('last_message_id')) {
            $query->where('co_chat_apl', '>', $request->last_message_id);
        }

        $mensajes = $query->orderBy('co_chat_apl', 'asc')
                     ->get()
                     ->map(function ($mensaje) {
                         // Si existe un mensaje respondido, formatear la información
                         if ($mensaje->repliedMessage) {
                             $mensaje->reply_to = [
                                 'id' => $mensaje->repliedMessage->co_chat_apl,
                                 'text' => $mensaje->repliedMessage->tx_comentario,
                                 'sender' => $mensaje->repliedMessage->user->name . ' ' . 
                                           $mensaje->repliedMessage->user->surname 
                                           //. '/' . 
                                           //$mensaje->repliedMessage->usuarioChatAplicacion->tx_perfil
                             ];
                         }
                         return $mensaje;
                     });

        return response()->json([
            'success' => true,
            'data' => $mensajes
        ]);
    }
    
    public function destroy(Request $request){
            try {
                // Registrar la finalización del chat
                Log::info('Chat finalizado', [
                    'user_id' => auth()->id(),
                    'co_aplicacion' => $request->input('co_aplicacion'),
                    'timestamp' => now()
                ]);

                // Limpiar la sesión
                if (session()->exists('history_messages')) {
                    $request->session()->forget('history_messages');
                }
                
                return response()->json([
                    'success' => true,
                    'message' => 'Sesión finalizada correctamente'
                ]);
            } catch (\Exception $e) {
                Log::error('Error al finalizar chat', [
                    'error' => $e->getMessage(),
                    'user_id' => auth()->id()
                ]);

                return response()->json([
                    'success' => false,
                    'message' => 'Error al finalizar la sesión'
                ], 500);
            }
    }
}
