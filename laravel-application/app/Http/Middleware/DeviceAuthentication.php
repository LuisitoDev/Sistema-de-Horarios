<?php

namespace App\Http\Middleware;

use App\Models\Dispositivo;
use App\Models\SolicitudDispositivo;
use Closure;
use Log;

use Illuminate\Http\Request;

class DeviceAuthentication
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

        if($request->ip() == $request->server('SERVER_ADDR'))
            return redirect('/admin');

        // $userToken = session('userToken');

        // if($userToken == null) {
        $mac = get_client_mac($request->ip());

        if($mac == null)
            return redirect('/error')->with(['errorMessage'=> 'No se ha podido procesar tu solicitud.']);



        $device = Dispositivo::join('usuarios', 'dispositivos.id_usuario', '=', 'usuarios.id')->where('dispositivos.direccion_mac', '=', $mac)->first();

        if($device){
            // Hacer logica para autenticar (Puede ser mediente otro middleware o realizarlo aqui mismo)

            $request->session()->invalidate();
            $request->session()->regenerate();
            session(['userToken' => true]);

        }else {
            // Buscar si el dispositivo esta siendo esperado a ser aceptado en una solicitud
            $request->session()->invalidate();
            $previousDeviceRequest = SolicitudDispositivo::join('usuarios', 'id_usuario', 'usuarios.id')->firstWhere('direccion_mac_dispositivo', $mac);

            if($previousDeviceRequest != null){

                // return view('asesor.solicitud-pendiente', compact('previousDeviceRequest'));

                return redirect('/registro/dispositivo');
            }

            return redirect('/registro');
        }


        // }


        return $next($request);
    }


}
