<?php

namespace App\Repositories\UsuarioPrograma;

use App\Models\UsuarioPrograma;

class UsuarioProgramaRepository{

    public function forceDeleteByData($data)
    {
        return UsuarioPrograma::where($data)->forceDelete();
    }
}