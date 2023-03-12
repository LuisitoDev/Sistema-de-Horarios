<?php

namespace App\Repositories\TurnoDiario;

use App\Models\TurnoDiario;
use Illuminate\Support\Facades\Log;

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
            ->where(TurnoDiario::id_horario, $id_horario)
            ->where(TurnoDiario::dia, $dia)
            ->orderBy(TurnoDiario::hora_entrada, 'ASC')->get();
    }

}