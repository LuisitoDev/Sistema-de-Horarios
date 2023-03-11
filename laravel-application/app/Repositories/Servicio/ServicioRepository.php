<?php

namespace App\Repositories\Servicio;

use App\Models\Servicio;

class ServicioRepository{

    public function getHorasTotalesByServicioUsuario($id_usuario)
    {
        return Servicio::join('usuarios_servicios', 'usuarios_servicios.id_servicio', '=', 'servicios.id')
        ->join('usuarios', 'usuarios_servicios.id_usuario', '=', 'usuarios.id')
        ->where('usuarios.id', '=', $id_usuario)->sum('horas_totales');
    }

}