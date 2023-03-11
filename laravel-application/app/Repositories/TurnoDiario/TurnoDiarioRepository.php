<?php

namespace App\Repositories\TurnoDiario;

use App\Models\TurnoDiario;

class TurnoDiarioRepository{

    public function find($data)
    {
        return TurnoDiario::query()
            ->where($data)->get()
            ->map->format();
    }

    public function findByHorarioAndDia($id_horario, $dia)
    {
        return TurnoDiario::query()
            ->where('id_horario', $id_horario)
            ->where('dia', $dia)
            ->orderBy('hora_entrada', 'ASC')->get()
            ->map->format();
    }

}