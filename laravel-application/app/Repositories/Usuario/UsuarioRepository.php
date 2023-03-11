<?php

namespace App\Repositories\Usuario;

use App\Models\Usuario;
use Illuminate\Support\Facades\DB;
use App\Enums\StatusType;
use Illuminate\Support\Facades\Log;

class UsuarioRepository{

    public function save($usuario)
    {
        return $usuario->save();
    } 

    public function findByDateWithTrashed($date)
    {
        return Usuario::whereDate('created_at', '<=', $date)
            ->withTrashed()->get()
            ->map->format();
    }

    public function find($data)
    {
        return Usuario::query()
            ->where($data)->get()
            ->map->format();        
    }

    public function findFirst($data)
    {
        $usuario = Usuario::query()
            ->where($data)
            ->get()->take(1);

        return count($usuario) > 0 ? $usuario->map->format()[0] : null;
    }
    
    public function findByFieldBetweenDates($search, $dayFrom, $dayTo)
    {
        return Usuario::join('ciclo_escolar', 'ciclo_escolar.id', '=', 'usuarios.id_ciclo_escolar')
            ->when($search != "", function($query) use ($search){
                return $query->where(function ($query) use ($search) {
                    $query->where('usuarios.matricula', 'like', '%'. $search.'%')
                    ->orWhere('usuarios.nombre', 'like', '%'.$search.'%')
                    ->orWhere('usuarios.apellido_pat', 'like', '%'.$search.'%')
                    ->orWhere('usuarios.apellido_mat', 'like', '%'.$search.'%');
                });
            })
            ->when($dayFrom != null && $dayTo != null, function($query) use ($dayFrom, $dayTo){
                return $query->where(function ($query) use ($dayFrom, $dayTo) {
                    $query->whereRaw(
                        "(ciclo_escolar.fecha_ingreso <= ? && ciclo_escolar.fecha_salida >= ?)
                        OR  (ciclo_escolar.fecha_ingreso <= ? && ciclo_escolar.fecha_salida >= ?)
                        OR  (ciclo_escolar.fecha_ingreso >= ? && ciclo_escolar.fecha_salida <= ?)",
                        [$dayFrom, $dayFrom, $dayTo, $dayTo, $dayFrom, $dayTo]);
                });
            })->get()
            ->map->format();
    }


    public function findByFieldBetweenDatesWithPagination($search, $dayFrom, $dayTo, $page, $elements)
    {
        return Usuario::select('usuarios.id',
            'usuarios.nombre',
            'usuarios.apellido_pat',
            'usuarios.apellido_mat',
            'usuarios.matricula',
            'correo_universitario',
            'abreviacion',
            'servicios.nombre as servicio_nombre')
            ->leftjoin('carreras', 'usuarios.id_carrera','=', 'carreras.id')
            ->leftjoin('usuarios_servicios', 'usuarios_servicios.id_usuario', '=', 'usuarios.id')
            ->leftjoin('servicios', 'usuarios_servicios.id_servicio', '=', 'servicios.id')
            ->join('ciclo_escolar', 'ciclo_escolar.id', '=', 'usuarios.id_ciclo_escolar')
            ->when($search != "", function($query) use ($search){
                return $query->where(function ($query) use ($search) {
                    $query->where('usuarios.matricula', 'like', '%'. $search.'%')
                    ->orWhere('usuarios.nombre', 'like', '%'.$search.'%')
                    ->orWhere('usuarios.apellido_pat', 'like', '%'.$search.'%')
                    ->orWhere('usuarios.apellido_mat', 'like', '%'.$search.'%');
                });
            })
            ->when($dayFrom != null && $dayTo != null, function($query) use ($dayFrom, $dayTo){
                return $query->where(function ($query) use ($dayFrom, $dayTo) {
                    $query->whereRaw(
                        "(ciclo_escolar.fecha_ingreso <= ? && ciclo_escolar.fecha_salida >= ?)
                        OR  (ciclo_escolar.fecha_ingreso <= ? && ciclo_escolar.fecha_salida >= ?)
                        OR  (ciclo_escolar.fecha_ingreso >= ? && ciclo_escolar.fecha_salida <= ?)",
                        [$dayFrom, $dayFrom, $dayTo, $dayTo, $dayFrom, $dayTo]);
                });
            })
            ->skip($page * $elements - $elements)
            ->take($elements)->get()
            ->map->format();
    }
    

    public function findStudentsChecks($search, $dayFrom, $dayTo, $page, $elements)
    {
        return Usuario::select('usuarios.id', 'usuarios.matricula', 'usuarios.nombre', 'usuarios.apellido_pat', 'usuarios.apellido_mat',
                'usuarios.correo_universitario', 'usuarios.id_carrera','carreras.abreviacion','servicios.nombre as servicio_nombre',
                DB::raw("count(entradas.id_usuario) as entradas"), DB::raw("sum(entradas.horas_realizadas_programada) - sum(entradas.horas_realizadas) as horas_pendientes")
                )
                ->leftjoin('carreras', 'usuarios.id_carrera','=', 'carreras.id')
                ->join('ciclo_escolar', 'ciclo_escolar.id', '=', 'usuarios.id_ciclo_escolar')
                ->leftjoin('usuarios_servicios', 'usuarios_servicios.id_usuario', '=', 'usuarios.id')
                ->leftjoin('servicios', 'usuarios_servicios.id_servicio', '=', 'servicios.id')
                ->leftJoin('entradas', function ($leftJoin) use ($dayFrom, $dayTo){
                    $leftJoin->on('usuarios.id', '=', 'entradas.id_usuario')
                    ->where('entradas.id_status', '!=', StatusType::TRABAJANDO)
                    ->whereDate('entradas.hora_entrada_programada', '>=', $dayFrom)
                    ->whereDate('entradas.hora_entrada_programada', '<=', $dayTo);
                })
                ->groupBy('usuarios.id', 'usuarios.matricula', 'usuarios.nombre', 'usuarios.apellido_pat', 'usuarios.apellido_mat',
                'usuarios.correo_universitario', 'usuarios.id_carrera','carreras.abreviacion', 'servicio_nombre')
                ->when($search != "", function($query) use ($search){
                    return $query->where(function ($query) use ($search) {
                        $query->where('usuarios.matricula', 'like', '%'. $search.'%')
                        ->orWhere('usuarios.nombre', 'like', '%'.$search.'%')
                        ->orWhere('usuarios.apellido_pat', 'like', '%'.$search.'%')
                        ->orWhere('usuarios.apellido_mat', 'like', '%'.$search.'%');
                    });
                })
                ->when($dayFrom != null && $dayTo != null, function($query) use ($dayFrom, $dayTo){
                    return $query->where(function ($query) use ($dayFrom, $dayTo) {
                        $query->whereRaw(
                            "(ciclo_escolar.fecha_ingreso <= ? && ciclo_escolar.fecha_salida >= ?)
                            OR  (ciclo_escolar.fecha_ingreso <= ? && ciclo_escolar.fecha_salida >= ?)
                            OR  (ciclo_escolar.fecha_ingreso >= ? && ciclo_escolar.fecha_salida <= ?)",
                            [$dayFrom, $dayFrom, $dayTo, $dayTo, $dayFrom, $dayTo]);
                    });
                })
                ->skip($page * $elements - $elements)
                ->take($elements)->get()
                ->map->format();
    }

    public function findProfileById($id_usuario)
    {
        return Usuario::select('usuarios.matricula','usuarios.apellido_mat','usuarios.apellido_pat','usuarios.imagen','usuarios.correo_universitario as correo',
            'usuarios.nombre as nombre','carreras.nombre as carrera','programas.nombre as programa'
            ,'dispositivos.direccion_mac','servicios.nombre as servicio', 'carreras.abreviacion')
            ->leftjoin('dispositivos','dispositivos.id_usuario','=','usuarios.id')
            ->leftJoin('usuarios_programas','usuarios_programas.id_usuario','=','usuarios.id')
            ->leftJoin('programas','programas.id','=','usuarios_programas.id_programa')
            ->join('carreras','carreras.id','=','usuarios.id_carrera')
            ->leftjoin('usuarios_servicios', 'usuarios_servicios.id_usuario', '=', 'usuarios.id')
            ->leftjoin('servicios', 'usuarios_servicios.id_servicio', '=', 'servicios.id')
            ->where('usuarios.id', '=', $id_usuario)
            ->first()
            ->map->format();
    }

    public function forceDeleteByDate($date)
    {
        return Usuario::whereDate('created_at', '<=', $date)
            ->withTrashed()->forceDelete()
            ->map->format();
    }
}