<?php 

namespace App\Console\ScheduledObjects;

use App\Enums\TareasEnum;
use App\Models\Usuario;
use App\Helpers\EntradaHelpers;
use App\Models\TareaEjecucion;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Throwable;

class CierreDeHoras {
    public function __invoke(){
 

        /* 
            1. Obtener la lista de alumnos que deben de acudir en el dia dependiendo la rotacion activa
            
            2. Iterar esa lista de alumnos y por cada iteracion revisar su entrada del dia

            3. Si la entrada fue cerrada correctamente omitirla, si no fue cerrada aplicar el corte y el trunqueo
                Si no asistio por completo, generar una entrada de 0 horas con status no cumplio entrada
            
        */

        try {
            $schedule = TareaEjecucion::whereDate('hora_ejecucion', '=', Carbon::today()->toDateString())->first();
            if($schedule ==  null || env('DEBUGGER')){
                $weekDay = date('N');

                $daysInDB = [
                    "1" => 'lunes_presencial',
                    "2" => 'martes_presencial',
                    "3" => 'miercoles_presencial',
                    "4" => 'jueves_presencial',
                    "5" => 'viernes_presencial'
                ];
               
                // Estudiantes que vienen en presencial
                $students = Usuario::select('usuarios.*')->join('rotaciones', 'usuarios.id_rotacion', '=', 'rotaciones.id')
                                ->where('rotaciones.'.$daysInDB[$weekDay], true)->get();
        
        
                
                foreach($students as $student)  {
                    EntradaHelpers::DetermineStatusAfterEndOfDay($student->id);
                }
        
                // Estudiantes que se quedan en linea
        
                $students = Usuario::select('usuarios.*')->join('rotaciones', 'usuarios.id_rotacion', '=', 'rotaciones.id')
                                ->where('rotaciones.'.$daysInDB[$weekDay], false)->get();
        
        
                foreach($students as $student) {
                    EntradaHelpers::DetermineStatusEndOfDayOnline($student->id);
                    //TODOD: RESOLVER QUE FUNCION LLAMAR DetermineStatusAfterEndOfDay
                }

                $schedule = new TareaEjecucion();
                $schedule->id_tarea_programada = TareasEnum::CIERRE_HORAS;
                $schedule->save(); //TODOD: PASAR ESTE SAVE A REPOSITORY
                Log::info('[CIERRE DE HORAS] El cierre de horas fue ejecutado exitosamente');
            } else{
                Log::info('[CIERRE DE HORAS] El cierre de horas ya fue ejecutado una vez en el dia, no se puede volver a ejecutar');
            }

        } catch(Throwable $exception){
            Log::error('[CIERRE DE HORAS] Ocurrio un error mientras se ejecutaba el cierre de horas: '.$exception->getMessage());
        }

        
    }
}


