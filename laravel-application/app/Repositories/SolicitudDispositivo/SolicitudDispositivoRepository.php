<?php

namespace App\Repositories\SolicitudDispositivo;

use App\Models\SolicitudDispositivo;
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
        ->where('id', $id_solicitud_dispositivo)->first();
        // ->map->format();
    }

    public function find($data)
    {
        return SolicitudDispositivo::select('solicitudes_dispositivos.id as id_solicitud', 'usuarios.correo_universitario')
            ->leftjoin('usuarios', 'id_usuario', 'usuarios.id')
            ->where($data)->get()
            ->map->format();
    }

    public function findFirst($data)
    {
        $solicitudDispositivo = SolicitudDispositivo::query()
            ->select('solicitudes_dispositivos.id as id_solicitud', 'usuarios.correo_universitario')
            ->leftjoin('usuarios', 'id_usuario', 'usuarios.id')
            ->where($data)
            ->get()->take(1);

        return count($solicitudDispositivo) > 0 ? $solicitudDispositivo->map->format()[0] : null;
    }
    
    public function findInDates($fecha_ingreso, $fecha_salida, $page, $elements)
    {

        //TODO: REFACTOR CODE
        return SolicitudDispositivo::select(
            'solicitudes_dispositivos.id',
            'solicitudes_dispositivos.created_at',
            'direccion_mac_dispositivo',
            'solicitudes_dispositivos.id_usuario',
            'usuarios.nombre',
            'apellido_pat',
            'apellido_mat',
            'matricula',
            'correo_universitario',
            'fecha_creacion',
            'abreviacion',
            'servicios.nombre AS servicio_nombre')
        ->leftjoin('usuarios', 'solicitudes_dispositivos.id_usuario', '=', 'usuarios.id')
        ->leftjoin('carreras', 'usuarios.id_carrera', '=', 'carreras.id')
        ->leftjoin('usuarios_servicios', 'usuarios_servicios.id_usuario', '=', 'usuarios.id')
        ->leftjoin('servicios', 'usuarios_servicios.id_servicio', '=', 'servicios.id')
        ->whereDate('solicitudes_dispositivos.created_at', '>=', $fecha_ingreso)
        ->whereDate('solicitudes_dispositivos.created_at', '<=', $fecha_salida)
        ->skip($page * $elements - $elements)
        ->take($elements)
        ->get();
    }


    
    public function deleteById($id_solicitud_dispositivo)
    {
       return SolicitudDispositivo::where('id', $id_solicitud_dispositivo)->delete();
    }
    

    
}