<?php

namespace App\Repositories\Admin;

use App\Models\Programa;

class ProgramaRepository{

    public function find($data)
    {
        return Programa::query()
            ->where($data)->get()
            ->map->format();
    }

    public function pluckByIdNombre(){
        return Programa::pluck('id', 'nombre');
    }
}