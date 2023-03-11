<?php

namespace App\Repositories\Admin;

use App\Models\Administrador;

class AdministradorRepository{

    public function find($data)
    {
        return Administrador::query()
            ->where($data)->get()
            ->map->format();
    }
}