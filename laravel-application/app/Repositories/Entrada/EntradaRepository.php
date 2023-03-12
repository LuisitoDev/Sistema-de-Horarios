<?php

namespace App\Repositories\Entrada;

use App\Models\Entrada;
use Illuminate\Support\Facades\DB;
use App\Enums\StatusType;
use Illuminate\Support\Facades\Log;

class EntradaRepository{

    public function save($entrada)
    {
        return $entrada->save();
    } 

    public function getEntradaModel()
    {
        return new Entrada();
    }

    public function findByUserBetweenDates($id_usuario, $dayFrom, $dayTo, $pagination = null, $elements = null)
    {   
        $query = (new Entrada)->newQuery();

        $query->where(Entrada::id_usuario, $id_usuario);

        //TODO: HACER QUE LA FECHA YA VENGA FORMATEADA DESDE EL FRONT
        if ($dayFrom)
            $query->where(Entrada::hora_entrada_programada, '>=', $dayFrom ." 00:00:00");

        if ($dayTo)
            $query->where(Entrada::hora_entrada_programada, '<=', $dayTo ." 23:59:59");

        if ($pagination !== null && $elements !== null)
            $query->offset($pagination)->limit($elements)->orderBy(Entrada::hora_entrada_programada, 'DESC');

        return $query->get()
            ->map->format();
    }

    public function getSumHorasRealizadasByUsuario($id_usuario)
    {        
        return Entrada::query()
            ->where(Entrada::id_usuario, $id_usuario)
            ->get()->sum(Entrada::horas_realizadas);
    }

    public function getHorasPendientesByUsuario($id_usuario)
    {
        return Entrada::select(DB::raw('sum(' . Entrada::horas_realizadas_programada . ') - sum(' . Entrada::horas_realizadas . ') as horas_pendientes'))
            ->where(Entrada::id_usuario, $id_usuario)
            ->where(Entrada::id_status, "!=", StatusType::TRABAJANDO)
            ->first()
            ->horas_pendientes;
    }

    public function findByUsuarioAndStatus($id_usuario, $status)
    {
        $entradas = Entrada::query()
        ->where(Entrada::id_usuario, $id_usuario)
        ->where(Entrada::id_status, $status)->get();
        
        return count($entradas) > 0 ? $entradas->map->format()[0] : null;
    }

    public function forceDeleteByData($data)
    {
        return Entrada::where($data)->forceDelete();
    }

    public function findByUsuarioAndHoraEntrada($id_usuario, $date)
    {
        return Entrada::query()
            ->where(Entrada::id_usuario, $id_usuario)
            ->whereDate(Entrada::hora_entrada, $date)->get()
            ->map->format();
    }

    public function getHorasRealizadasByUsuario($id_usuario)
    {
        return Entrada::query()
            ->where(Entrada::id_usuario, $id_usuario)
            ->where(Entrada::id_status, "!=", StatusType::TRABAJANDO)
            ->sum(Entrada::horas_realizadas);
    }

    public function getHorasRealizadasProgramadaByUsuario($id_usuario)
    {
        return Entrada::query()
            ->where(Entrada::id_usuario, $id_usuario)
            ->where(Entrada::id_status, "!=", StatusType::TRABAJANDO)
            ->sum(Entrada::horas_realizadas_programada);
    }

    public function findByUsuarioAndHoraEntradaProgramada($id_usuario, $hora_entrada_programada)
    {
        $entradas = Entrada::query()
        ->where(Entrada::id_usuario, $id_usuario)
        ->whereDate(Entrada::hora_entrada_programada, $hora_entrada_programada)
        ->orderBy(Entrada::hora_entrada, 'asc')->get();
        
        return count($entradas) > 0 ? $entradas->map->format() : null;
    }

    public function findUltimaEntradaByUsuario($id_usuario)
    {
        $entradas = Entrada::query()
            ->where(Entrada::id_usuario, $id_usuario)
            ->orderBy(Entrada::hora_entrada, 'desc')->get();

        return count($entradas) > 0 ? $entradas->map->format()[0] : null;
    }

    public function findEntradaActivaByUsuario($id_usuario)
    {
        return Entrada::query()
            ->where(Entrada::hora_salida, null)
            ->where(Entrada::id_usuario, $id_usuario)
            ->where(Entrada::id_status, StatusType::TRABAJANDO)
            ->first()
            ->map->format();
    }
}