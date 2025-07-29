<?php

namespace App\Services;

use App\Models\C038tOrdenes;
use Illuminate\Support\Facades\Auth;

class InstalationOrderService
{
    /**
     * Obtiene el total de órdenes de instalación para el usuario autenticado
     * @return int
     */
    public function getTotalOrdenesUsuario(): int
    {
        $userId = Auth::id();
        
        if (!$userId) {
            return 0;
        }

        return C038tOrdenes::where('co_manager', $userId)->count();
    }

    /**
     * Obtiene el total de órdenes para un usuario específico
     * @param int $userId
     * @return int
     */
    public function getTotalOrdenesPorUsuario($userId): int
    {
        if (!$userId) {
            return 0;
        }

        return C038tOrdenes::where('co_manager', $userId)->count();
    }

    /**
     * Obtiene las órdenes de instalación para el usuario autenticado
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getOrdenesUsuario()
    {
        $userId = Auth::id();
        
        if (!$userId) {
            return collect();
        }

        return C038tOrdenes::where('co_manager', $userId)->get();
        
    }
} 