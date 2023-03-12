<?php

namespace App\Repositories\Horario;

use App\Models\Horario;

class HorarioRepository{

    //TODO: REFACTOR
    public function find($data)
    {
        return Horario::query()
            ->where($data)->get()
            ->map->format();
    }

    public function forceDeleteByData($data)
    {
        return Horario::where($data)->forceDelete();
    }
}