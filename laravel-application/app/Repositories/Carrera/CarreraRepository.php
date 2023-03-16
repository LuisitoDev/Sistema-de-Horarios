<?php

namespace App\Repositories\Admin;

use App\Models\Carrera;

class CarreraRepository{

    public function find($data)
    {
        return Carrera::query()
            ->where($data)->get()
            ->map->format();
    }

    public function pluckByIdAbreviacion(){
        return Carrera::pluck('id', 'nombre');
    }
}