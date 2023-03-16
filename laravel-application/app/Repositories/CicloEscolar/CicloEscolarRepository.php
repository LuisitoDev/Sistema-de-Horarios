<?php

namespace App\Repositories\CicloEscolar;

use App\Models\CicloEscolar;
use Illuminate\Support\Facades\Log;

class CicloEscolarRepository{

    public function findAll()
    {
       return CicloEscolar::query()
        ->orderBy(CicloEscolar::fecha_ingreso, 'DESC')->get()
        ->map->format();
    }

    public function findById($id_ciclo_escolar)
    {
       $cicloEscolar = CicloEscolar::query()
        ->where(CicloEscolar::id, $id_ciclo_escolar)
        ->get()->take(1);
        
        return count($cicloEscolar) > 0 ? $cicloEscolar->map->format()[0] : null;
    }

    public function findLast()
    {
       $cicloEscolar = CicloEscolar::query()
        ->orderBy('fecha_ingreso', 'DESC')
        ->get()->take(1);
        
        return count($cicloEscolar) > 0 ? $cicloEscolar->map->format()[0] : null;
    }

}