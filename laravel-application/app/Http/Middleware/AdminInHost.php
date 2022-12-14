<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AdminInHost
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

        $ip = $request->ip();

        if($ip != $request->server('SERVER_ADDR')){
            return redirect('/error')->with(['errorMessage' => 'No tienes permitido el acceso.']);
        }

       


        return $next($request);
    }
}
