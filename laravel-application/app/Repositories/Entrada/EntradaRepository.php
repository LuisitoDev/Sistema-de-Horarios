<?php

namespace App\Repositories\Entrada;

use App\Models\Entrada;
use Illuminate\Support\Facades\DB;
use App\Enums\StatusType;

class EntradaRepository{

    public function save($entrada)
    {
        return $entrada->save();
    } 

    public function getEntradaModel()
    {
        return new Entrada();
    }

    public function findByUserBetweenDatesWithPagination($id_usuario, $dayFrom, $dayTo, $pagination, $elements)
    {
        return Entrada::whereRaw(
            "
            id_usuario = ?
            AND     IF(? is null, 1, hora_entrada_programada >= ?)
            AND     IF(? is null, 1, hora_entrada_programada <=  ?)",
            [
                $id_usuario,
                $dayFrom,
                $dayFrom,
                $dayTo,
                $dayTo
            ])->offset($pagination)->limit($elements)->orderBy('hora_entrada_programada', 'DESC')->get()
            ->map->format();
    }

    public function findByUserBetweenDates($id_usuario, $dayFrom, $dayTo)
    {
        return Entrada::whereRaw(
            "
            id_usuario = ?
            AND     IF(? is null, 1, hora_entrada_programada >= ?)
            AND     IF(? is null, 1, hora_entrada_programada <=  ?)",
            [
                $id_usuario,
                $dayFrom,
                $dayFrom,
                $dayTo,
                $dayTo
            ])->get()
            ->map->format();
    }

    public function getSumHorasRealizadasByUsuario($id_usuario)
    {
        return Entrada::where('id_usuario', "=", $id_usuario)
        ->get()
        ->sum('horas_realizadas');
    }

    public function getHorasPendientesByUsuario($id_usuario)
    {
        return Entrada::select(DB::raw('sum(entradas.horas_realizadas_programada) - sum(entradas.horas_realizadas) as horas_pendientes'))
        ->where('entradas.id_usuario', "=", $id_usuario)
        ->where('entradas..id_status', "!=", StatusType::TRABAJANDO)
        ->first()
        ->horas_pendientes;
    }

    public function findByUsuarioAndStatus($id_usuario, $status)
    {
        return Entrada::where('id_usuario', $id_usuario)
        ->where('id_status', $status)->first()
        ->map->format();
    }

    public function forceDeleteByData($data)
    {
        return Entrada::where($data)->forceDelete();
    }

    public function findByUsuarioAndHoraEntrada($id_usuario, $date)
    {
        return Entrada::query()
            ->where('id_usuario', $id_usuario)
            ->whereDate('hora_entrada', $date)->get()
            ->map->format();
    }

    public function getHorasRealizadasByUsuario($id_usuario)
    {
        return Entrada::query()
            ->where([['id_usuario', "=", $id_usuario], ['id_status', "!=", StatusType::TRABAJANDO]])
            ->sum('horas_realizadas');
    }

    public function getHorasRealizadasProgramadaByUsuario($id_usuario)
    {
        return Entrada::query()
            ->where([['id_usuario', "=", $id_usuario], ['id_status', "!=", StatusType::TRABAJANDO]])
            ->sum('horas_realizadas_programada');
    }

    public function findByUsuarioAndHoraEntradaProgramada($id_usuario, $hora_entrada_programada)
    {
        return Entrada::query()
            ->where('id_usuario', $id_usuario)
            ->whereDate('hora_entrada_programada', $hora_entrada_programada)
            ->orderBy('hora_entrada', 'asc')->get()
            ->map->format();
    }

    public function findUltimaEntradaByUsuario($id_usuario)
    {
        return Entrada::query()
            ->where('id_usuario', $id_usuario)
            ->orderBy('hora_entrada', 'desc')->first()
            ->map->format();
    }

    public function findEntradaActivaByUsuario($id_usuario)
    {
        return Entrada::query()
            ->where('hora_salida', null)
            ->where('id_usuario', $id_usuario)
            ->where('id_status',StatusType::TRABAJANDO)
            ->first()
            ->map->format();
    }
}