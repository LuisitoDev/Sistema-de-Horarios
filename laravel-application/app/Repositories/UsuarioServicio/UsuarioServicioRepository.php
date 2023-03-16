<?php

namespace App\Repositories\UsuarioServicio;

use App\Models\UsuarioServicio;

class UsuarioServicioRepository{

    public function getByUsuario($id_usuario)
    {
        $usuario_servicio = UsuarioServicio::query()
        ->where(UsuarioServicio::id_usuario, $id_usuario)
        ->get()->take(1);
        
        return count($usuario_servicio) > 0 ? $usuario_servicio->map->format()[0] : null;
    }

    
    public function create($id_usuario, $id_servicio)
    {        
        return UsuarioServicio::create([
            UsuarioServicio::id_usuario     => $id_usuario,
            UsuarioServicio::id_servicio    => $id_servicio
        ]);
    }
}