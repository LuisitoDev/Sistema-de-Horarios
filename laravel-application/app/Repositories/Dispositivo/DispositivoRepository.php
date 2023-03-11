<?php

namespace App\Repositories\Dispositivo;

use App\Models\Dispositivo;
use Illuminate\Support\Facades\Log;

class DispositivoRepository{

    public function create($data)
    {
        return  Dispositivo::create($data);
    }

    public function find($data)
    {
        return Dispositivo::query()
            ->where($data)->get()
            ->map->format();
    }

    public function findFirst($data)
    {
        $dispositivo = Dispositivo::query()
            ->where($data)
            ->get()->take(1);

        return count($dispositivo) > 0 ? $dispositivo->map->format()[0] : null;
    }

    public function forceDeleteByData($data)
    {
        return Dispositivo::where($data)->forceDelete();
    }
    
}