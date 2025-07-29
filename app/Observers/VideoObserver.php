<?php

namespace App\Observers;

use App\Models\Video;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class VideoObserver
{
    public function created(Video $video)
    {
        // Get all users
        $users = DB::table('users')->get();

        foreach ($users as $user) {
            // Update or create notification counter
            $notification = DB::table('c035t_usuarios_notificacion')
                ->where('co_usuario', $user->id)
                ->where('co_tiponoti', 5)
                ->first();

            if ($notification) {
                DB::table('c035t_usuarios_notificacion')
                    ->where('co_usuario', $user->id)
                    ->where('co_tiponoti', 5)
                    ->update([
                        'nu_notifica' => $notification->nu_notifica + 1,
                        'bo_visto' => false,
                        'fe_ultima_act' => Carbon::now()
                    ]);
            } else {
                DB::table('c035t_usuarios_notificacion')->insert([
                    'co_usuario' => $user->id,
                    'co_tiponoti' => 5,
                    'bo_visto' => false,
                    'nu_notifica' => 1,
                    'bo_visualizar' => true,
                    'fe_registro' => Carbon::now(),
                    'fe_ultima_act' => Carbon::now(),
                    'tx_info_general' => 'Nuevo video disponible'
                ]);
            }

            // Add to notification history
            DB::table('c036t_usuarios_notificacion_his')->insert([
                'co_usuario' => $user->id,
                'co_tiponoti' => 5,
                'tx_info_general' => "Nuevo video disponible: {$video->title}",
                'bo_visto' => false,
                'fe_registro' => Carbon::now()
            ]);
        }
    }
} 