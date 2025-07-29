<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\DB;

class ViewServiceProvider extends ServiceProvider
{
    public function register()
    {
        //
    }

    public function boot()
    {
        View::composer('users.create', function ($view) {
            // Get active users ordered by name
            $activeUsers = DB::table('c002t_usuarios')
                ->where('co_estatus_usuario', 1)
                ->select('co_usuario', 'tx_primer_nombre', 'tx_primer_apellido')
                ->orderBy('tx_primer_nombre')
                ->orderBy('tx_primer_apellido')
                ->get();

            // Get office managers ordered by name
            $officeManagers = DB::table('c002t_usuarios AS manager')
                ->whereIn('manager.co_usuario', function($query) {
                    $query->select('co_office_manager')
                          ->from('c002t_usuarios')
                          ->whereNotNull('co_office_manager')
                          ->distinct();
                })
                ->where('manager.co_estatus_usuario', 1)
                ->select('manager.co_usuario', 'manager.tx_primer_nombre', 'manager.tx_primer_apellido')
                ->orderBy('manager.tx_primer_nombre')
                ->orderBy('manager.tx_primer_apellido')
                ->get();

            // Get roles ordered by name
            $roles = DB::table('i007t_roles')
                ->orderBy('tx_nombre')
                ->get();

            // Get user types ordered by name
            $userTypes = DB::table('c013t_tipo_usuarios')
                ->orderBy('tx_tipo_usuario')
                ->get();

            // Get user statuses ordered by name
            $userStatuses = DB::table('i014t_estatus_usuarios')
                ->orderBy('tx_estatus')
                ->get();

            // Get offices ordered by city name
            $offices = DB::table('i008t_oficinas')
                ->select('co_oficina', 'tx_nombre', 'co_zip')
                ->orderBy(DB::raw('LOWER(tx_nombre)')) // Case-insensitive ordering
                ->get();
            
            $view->with([
                'roles' => $roles,
                'userTypes' => $userTypes,
                'userStatuses' => $userStatuses,
                'offices' => $offices,
                'activeUsers' => $activeUsers,
                'officeManagers' => $officeManagers
            ]);
        });

        // Add the edit view to the composer
        View::composer(['users.create', 'users.edit'], function ($view) {
            // Get active users ordered by name
            $activeUsers = DB::table('c002t_usuarios')
                ->where('co_estatus_usuario', 1)
                ->select('co_usuario', 'tx_primer_nombre', 'tx_primer_apellido')
                ->orderBy('tx_primer_nombre')
                ->orderBy('tx_primer_apellido')
                ->get();

            // Get office managers ordered by name
            $officeManagers = DB::table('c002t_usuarios AS manager')
                ->whereIn('manager.co_usuario', function($query) {
                    $query->select('co_office_manager')
                          ->from('c002t_usuarios')
                          ->whereNotNull('co_office_manager')
                          ->distinct();
                })
                ->where('manager.co_estatus_usuario', 1)
                ->select('manager.co_usuario', 'manager.tx_primer_nombre', 'manager.tx_primer_apellido')
                ->orderBy('manager.tx_primer_nombre')
                ->orderBy('manager.tx_primer_apellido')
                ->get();

            // Get roles ordered by name
            $roles = DB::table('i007t_roles')
                ->orderBy('tx_nombre')
                ->get();

            // Get user types ordered by name
            $userTypes = DB::table('c013t_tipo_usuarios')
                ->orderBy('tx_tipo_usuario')
                ->get();

            // Get user statuses ordered by name
            $userStatuses = DB::table('i014t_estatus_usuarios')
                ->orderBy('tx_estatus')
                ->get();

            // Get offices ordered by city name
            $offices = DB::table('i008t_oficinas')
                ->select('co_oficina', 'tx_nombre', 'co_zip')
                ->orderBy(DB::raw('LOWER(tx_nombre)')) // Case-insensitive ordering
                ->get();
            
            $view->with([
                'roles' => $roles,
                'userTypes' => $userTypes,
                'userStatuses' => $userStatuses,
                'offices' => $offices,
                'activeUsers' => $activeUsers,
                'officeManagers' => $officeManagers
            ]);
        });
    }
} 