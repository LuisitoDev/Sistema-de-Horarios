<?php

namespace App\Repositories\Usuario;

use App\Models\Usuario;
use Illuminate\Support\Facades\DB;
use App\Enums\StatusType;
use App\Models\Carrera;
use App\Models\CicloEscolar;
use App\Models\Dispositivo;
use App\Models\Entrada;
use App\Models\Programa;
use App\Models\Servicio;
use Illuminate\Support\Facades\Log;

//TODO: REFACTORIZAR COLUMNAS HARDCODEADAS
class UsuarioRepository{

    public function save($usuario)
    {
        return $usuario->save();
    } 

    public function findByDateWithTrashed($date)
    {
        return Usuario::whereDate(Usuario::created_at, '<=', $date)
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
    
    public function findByFieldBetweenDates($search, $dayFrom, $dayTo, $page = null, $elements = null)
    {
        $query = Usuario::query()->select("usuarios.*")
            ->with("carrera:" . Carrera::id . "," . Carrera::abreviacion)
            ->with("servicios:" . Servicio::id . "," . Servicio::nombre)

            //TODO: VER SI SE PUEDE HACER LO SIGUIENTE CON UN WITH
            // ->with("ciclo_escolar:" . CicloEscolar::id);
            ->join('ciclo_escolar', 'ciclo_escolar.id', '=', 'usuarios.id_ciclo_escolar');

        if ($dayFrom != null && $dayTo != null){
            $query->where(function ($query) use ($dayFrom, $dayTo) {
                $query->whereRaw(
                    "(ciclo_escolar.fecha_ingreso <= ? && ciclo_escolar.fecha_salida >= ?)
                    OR  (ciclo_escolar.fecha_ingreso <= ? && ciclo_escolar.fecha_salida >= ?)
                    OR  (ciclo_escolar.fecha_ingreso >= ? && ciclo_escolar.fecha_salida <= ?)",
                    [$dayFrom, $dayFrom, $dayTo, $dayTo, $dayFrom, $dayTo]);
            });
        }

        if ($search != ""){
            $query->where(function ($query) use ($search) {
                $query->where('usuarios.matricula', 'like', '%'. $search.'%')
                    ->orWhere('usuarios.nombre', 'like', '%'.$search.'%')
                    ->orWhere('usuarios.apellido_pat', 'like', '%'.$search.'%')
                    ->orWhere('usuarios.apellido_mat', 'like', '%'.$search.'%');
            });
        }

        if ($page !== null and $elements !== null){
            $query->skip($page * $elements - $elements)
            ->take($elements);
        }

        $usuarios = $query->get();
            
        return count($usuarios) > 0 ? $usuarios->map->format() : null;
    }

    public function findStudentsChecks($search, $dayFrom, $dayTo, $page = null, $elements = null)
    {
        $query = Usuario::query()->select("usuarios.*", DB::raw("count(". Entrada::id_usuario .") as cant_entradas"), DB::raw("sum(".Entrada::horas_realizadas_programada.") - sum(".Entrada::horas_realizadas.") as horas_pendientes"))
        ->with("carrera:" . Carrera::id . "," . Carrera::abreviacion)
        ->with("servicios:" . Servicio::id . "," . Servicio::nombre)

        //Este query no funciona si el usuario no tiene ninguna entrada, se podría mejorar pero mejor optamos por el tradicional leftJoin
        // ->with("entradas", function($query) use($dayFrom, $dayTo){

        //     $query->select(Entrada::id_usuario, DB::raw("count(". Entrada::id_usuario .") as cant_entradas"), DB::raw("sum(".Entrada::horas_realizadas_programada.") - sum(".Entrada::horas_realizadas.") as horas_pendientes"))
        //     ->where(Entrada::id_status, '!=', StatusType::TRABAJANDO)
        //     ->whereDate(Entrada::hora_entrada_programada, '>=', $dayFrom)
        //     ->whereDate(Entrada::hora_entrada_programada, '<=', $dayTo)
        //     ->groupBy(Entrada::id_usuario);
            
        // })

        
        ->leftJoin('entradas', function ($leftJoin) use ($dayFrom, $dayTo){
            $leftJoin->on('usuarios.id', '=', 'entradas.id_usuario')
            ->where('entradas.id_status', '!=', StatusType::TRABAJANDO)
            ->whereDate('entradas.hora_entrada_programada', '>=', $dayFrom)
            ->whereDate('entradas.hora_entrada_programada', '<=', $dayTo);
        })
        
        //TODO: VER SI SE PUEDE HACER LO SIGUIENTE CON UN WITH
        // ->with("ciclo_escolar:" . CicloEscolar::id);
        ->join('ciclo_escolar', 'ciclo_escolar.id', '=', 'usuarios.id_ciclo_escolar');

        if ($dayFrom != null && $dayTo != null){
            $query->where(function ($query) use ($dayFrom, $dayTo) {
                $query->whereRaw(
                    "(ciclo_escolar.fecha_ingreso <= ? && ciclo_escolar.fecha_salida >= ?)
                    OR  (ciclo_escolar.fecha_ingreso <= ? && ciclo_escolar.fecha_salida >= ?)
                    OR  (ciclo_escolar.fecha_ingreso >= ? && ciclo_escolar.fecha_salida <= ?)",
                    [$dayFrom, $dayFrom, $dayTo, $dayTo, $dayFrom, $dayTo]);
            });
        }

        if ($search != ""){
            $query->where(function ($query) use ($search) {
                $query->where('usuarios.matricula', 'like', '%'. $search.'%')
                    ->orWhere('usuarios.nombre', 'like', '%'.$search.'%')
                    ->orWhere('usuarios.apellido_pat', 'like', '%'.$search.'%')
                    ->orWhere('usuarios.apellido_mat', 'like', '%'.$search.'%');
            });
        }

        if ($page !== null and $elements !== null){
            $query->skip($page * $elements - $elements)
            ->take($elements);
        }

        $query->groupBy("usuarios.id");

        
        $usuarios = $query->get();

            
        return count($usuarios) > 0 ? $usuarios->map->format() : null;
    }

    public function findProfileById($id_usuario)
    {

        $usuarios = Usuario::query()
            ->with("dispositivos:". Dispositivo::id . "," . Dispositivo::direccion_mac)
            ->with("programas:" . Programa::id . ",". Programa::nombre)
            ->with("servicios:" . Servicio::id . "," . Servicio::nombre)
            ->with("carrera:" . Carrera::id . "," . Carrera::abreviacion)
            ->where(Usuario::table_name . "." . Usuario::id, '=', $id_usuario)
            ->get();

        return count($usuarios) > 0 ? $usuarios->map->format()[0] : null;
    }

    public function forceDeleteByDate($date)
    {
        return Usuario::whereDate(Usuario::created_at, '<=', $date)
            ->withTrashed()->forceDelete()
            ->map->format();
    }
}