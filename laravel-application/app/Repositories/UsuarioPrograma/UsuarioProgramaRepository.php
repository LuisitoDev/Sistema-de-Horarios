<?php

namespace App\Repositories\UsuarioPrograma;

use App\Models\UsuarioPrograma;

class UsuarioProgramaRepository{
    
    public function getByUsuario($id_usuario)
    {
        $usuario_programa = UsuarioPrograma::query()
        ->where(UsuarioPrograma::id_usuario, $id_usuario)
        ->get()->take(1);
        
        return count($usuario_programa) > 0 ? $usuario_programa->map->format()[0] : null;
    }

    
    public function create($id_usuario, $id_programa)
    {        
        return UsuarioPrograma::create([
            UsuarioPrograma::id_usuario     => $id_usuario,
            UsuarioPrograma::id_programa    => $id_programa
        ]);
    }

    public function forceDeleteByData($data)
    {
        return UsuarioPrograma::where($data)->forceDelete();
    }
}