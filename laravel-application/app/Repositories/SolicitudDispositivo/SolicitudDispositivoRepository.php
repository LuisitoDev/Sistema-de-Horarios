<?php

namespace App\Repositories\SolicitudDispositivo;

use App\Models\Carrera;
use App\Models\Servicio;
use App\Models\SolicitudDispositivo;
use App\Models\SolicitudUsuario;
use App\Models\Usuario;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SolicitudDispositivoRepository{

    public function create($data)
    {
        return  SolicitudDispositivo::create($data);
    }

    public function findAll()
    {
       return SolicitudDispositivo::query()
        ->get()
        ->map->format();
    }

    public function findById($id_solicitud_dispositivo)
    {
       return SolicitudDispositivo::query()
        ->where(SolicitudDispositivo::id, $id_solicitud_dispositivo)->first();
        // ->map->format();
    }

    public function find($data)
    {
        return SolicitudDispositivo::query()
            ->select(SolicitudDispositivo::id  . " as id_solicitud", SolicitudDispositivo::id_usuario)
            ->with('usuario:' . Usuario::id .",". Usuario::correo_universitario)
            ->where($data)->get();
    }

    public function findFirst($data)
    {
        $solicitudDispositivo = SolicitudDispositivo::query()
            ->select(SolicitudDispositivo::id  . " as id_solicitud", SolicitudDispositivo::id_usuario)
            ->with('usuario:' . Usuario::id .",". Usuario::correo_universitario)
            ->where($data)
            ->get()->take(1);

        return count($solicitudDispositivo) > 0 ? $solicitudDispositivo->map->format()[0] : null;
    }
    
    public function findInDates($fecha_ingreso, $fecha_salida, $page, $elements)
    {
        return SolicitudDispositivo::query()->with(['usuario' => function($query){
            $query->with("carrera:" . Carrera::id . "," . Carrera::abreviacion)->with("servicios:" . Servicio::id . "," . Servicio::nombre . " as servicio_nombre");
        }])
        ->whereDate(SolicitudDispositivo::table_name . "." . SolicitudDispositivo::created_at, '>=', $fecha_ingreso)
        ->whereDate(SolicitudDispositivo::table_name . "." . SolicitudDispositivo::created_at, '<=', $fecha_salida)
        ->skip($page * $elements - $elements)
        ->take($elements)->get();
    }


    
    public function deleteById($id_solicitud_dispositivo)
    {
       return SolicitudDispositivo::where(SolicitudDispositivo::id, $id_solicitud_dispositivo)->delete();
    }
    

    
}