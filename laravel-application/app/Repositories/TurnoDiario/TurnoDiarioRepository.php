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

    
    public function create($hora_entrada, $hora_salida, $dia, $id_horario)
    {        
        return  TurnoDiario::create([
            TurnoDiario::hora_entrada          => $hora_entrada,
            TurnoDiario::hora_salida           => $hora_salida,
            TurnoDiario::dia => $dia,
            TurnoDiario::id_horario => $id_horario,
        ]);
    }

    public function checkTurnoBloqueado($hora_entrada, $hora_salida, $dia_turno, $id_horario)
    {
        $turno = TurnoDiario::query()
            ->where(function ($query) use ($hora_entrada, $hora_salida) {
                $query->where(function ($query) use ($hora_entrada) {
                    $query->whereRaw(
                        "hora_entrada < CAST(? AS time) AND hora_salida > CAST(? AS time)",
                        [$hora_entrada, $hora_entrada] );
                })
                ->orWhere(function ($query) use ($hora_salida) {
                    $query->whereRaw(
                        "hora_entrada < CAST(? AS time) AND hora_salida > CAST(? AS time)",
                        [$hora_salida, $hora_salida] );
                })
                ->orWhere(function ($query) use ($hora_entrada, $hora_salida) {
                    $query->whereRaw(
                        "hora_entrada >= CAST(? AS time) AND hora_salida <= CAST(? AS time)",
                        [$hora_entrada, $hora_salida] );
                });
            })
            ->where(TurnoDiario::dia, $dia_turno)
            ->where(TurnoDiario::id_horario, $id_horario)
            ->get()->take(1);
        
        return count($turno) > 0 ? $turno->map->format()[0] : null;
    }
}