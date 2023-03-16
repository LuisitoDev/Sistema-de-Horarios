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

    public function getByUsuario($id_usuario)
    {
        $horario = Horario::query()
        ->where(Horario::id_usuario, $id_usuario)
        ->get()->take(1);
        
        return count($horario) > 0 ? $horario->map->format()[0] : null;
    }

    
    public function create($id_usuario)
    {        
        return Horario::create([
            Horario::id_usuario     => $id_usuario,
        ]);
    }

    public function forceDeleteByData($data)
    {
        return Horario::where($data)->forceDelete();
    }
}