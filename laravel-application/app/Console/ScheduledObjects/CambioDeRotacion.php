<?php

namespace App\Console\ScheduledObjects;

use App\Models\Usuario;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Throwable;

class CambioDeRotacion {
    public function __invoke(){
           // TODO: alternar la rotacion de todos los usuarios que tengan la rotacion de lunes miercoles viernes a -> martes y jueves
            //  y visceversa (UPDATE WHERE)

        try {
            DB::beginTransaction();


          
            DB::statement('UPDATE usuarios SET id_rotacion = CASE WHEN id_rotacion = 1 THEN 2 ELSE (CASE WHEN id_rotacion = 2 THEN 1 END) END WHERE id > 0');
        
            DB::commit();

        }catch(Throwable $e){
            Log::error($e);
            DB::rollback();
        }


    }
}