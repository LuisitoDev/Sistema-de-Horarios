<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Exception;
use App\Exceptions\ExceptionHandler;

class AdminAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $adminToken = session('adminToken');

        if($adminToken == null)
            // TODO Return json specifying that user has no permission
            return ExceptionHandler::respondExceptionJSON(new Exception('No se cuenta con los permisos de administrador para realizar esta accion'));

        return $next($request);
    }
}
