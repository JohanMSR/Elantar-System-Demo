<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Closure;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    protected function redirectTo(Request $request): ?string
    {
        return $request->expectsJson() ? null : route('login');
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  array  ...$guards
     * @return mixed
     */
    public function handle($request, Closure $next, ...$guards)
    {
        // Verificar si el usuario está autenticado
        if (!Auth::check()) {
            return redirect($this->redirectTo($request));
        }

        // Verificar si la sesión ha expirado
        if ($request->session()->has('last_activity') && (time() - $request->session()->get('last_activity') > config('session.lifetime') * 60)) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            return redirect($this->redirectTo($request));
        }

        // Actualizar el tiempo de la última actividad
        $request->session()->put('last_activity', time());

        return $next($request);
    }
}

