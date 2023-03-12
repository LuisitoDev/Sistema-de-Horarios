<?php

namespace App\Repositories\Servicio;

use App\Models\Servicio;
use App\Models\UsuarioServicio;
use App\Models\Usuario;

class ServicioRepository{

    public function getHorasTotalesByServicioUsuario($id_usuario)
    {
        return Servicio::whereHas(Usuario::table_name, function($query) use($id_usuario){
            $query->where(UsuarioServicio::id_usuario, $id_usuario);
        })->sum(Servicio::horas_totales);
    }

}