<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

class TipoUsuarioMiddleware
{
    public function handle(Request $request, Closure $next, $tipoUsuario = 3)
    {
        if (!Auth::check() || Auth::user()->co_tipo_usuario != $tipoUsuario) {
            return Redirect::to('/dashboard'); 
        }

        return $next($request);
    }
}