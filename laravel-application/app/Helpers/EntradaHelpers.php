<?php

namespace App\Helpers;

use App\Repositories\Entrada\EntradaRepository;
use App\Enums\StatusType;
use App\Enums\DiasEnum;
use App\Exceptions\CustomException;
use App\Repositories\Horario\HorarioRepository;
use App\Repositories\TurnoDiario\TurnoDiarioRepository;
use Illuminate\Support\Carbon;

use Illuminate\Support\Facades\Log;


class EntradaHelpers {
    public static $toleranceRangeCheckout = 0.02;
    public static $toleranceRangeOverTime = 0.5;
    private static EntradaRepository $entradaRepository;
    private static HorarioRepository $horarioRepository;
    private static TurnoDiarioRepository $turnoDiarioRepository;

    static function init()
    {
        self::$entradaRepository = new EntradaRepository();
        self::$horarioRepository = new HorarioRepository();
        self::$turnoDiarioRepository = new TurnoDiarioRepository();
    }

    public static function GetEntradaActiva($id_usuario) {
        // Busca si existe una entrada activa que aun no ha sido cerrada
        $entrada = self::$entradaRepository->findEntradaActivaByUsuario($id_usuario);

        //  Si no hay una entrada activa entonces regresa nulo para manejarlo segun sea conveniente
        if($entrada == null)
            return null;

        #region FACTORIZADO Settear hora de salida y horas realizadas
        $entrada = self::SetHorasSalidaYHorasRealizadas($entrada);
        #endregion

        return $entrada;
    }

    public static function GetEntradasRegistradasDia($id_usuario) {
        #region N/A Obtener entradas del dia de hoy
        $entradas = self::$entradaRepository->findByUsuarioAndHoraEntradaProgramada($id_usuario, Carbon::today());
        #endregion

        if ($entradas === null)
            $cant_entradas = 0;
        else
            $cant_entradas = count($entradas);

        return $cant_entradas;
    }

    public static function BuildEntradaDefault($id_usuario){
        // Instancia una entrada con los datos basicos necesarios, su hora de entrada y las potenciales horas que debio de hacer
        $entrada = self::$entradaRepository->getEntradaModel();
        $turnoDiario = self::GetTurnosDiariosUsuario($id_usuario);

        if(count($turnoDiario) == 0)
            return null;
        if (count($turnoDiario) == 1)
            $turnoDiario = $turnoDiario[0];
        else if(count($turnoDiario) > 1){
            //Aquí debemos buscar si ya tiene una entrada el día de hoy
            
            $cant_entradas = self::GetEntradasRegistradasDia($id_usuario);
            
            // Aqui se encuentra el siguiente posible turno de la ultima entrada programada. la hora de entrada y salida de ese posible turno se le asigna como las horas de entrada y salida programada en la nueva instancia de entrada
            if (isset($turnoDiario[$cant_entradas]))
                $turnoDiario = $turnoDiario[$cant_entradas];

            Log::info('Analizando multiturno con hora de entrada en: '.$turnoDiario->hora_entrada);
        }

        $entrada = self::SetHorasEntrada($entrada, $turnoDiario);

        $entrada->reporte_diario = '';
        $entrada->id_usuario = $id_usuario;

        return $entrada;
    }

    public static function DetermineStatusAfterEndOfDay($id_usuario){

        Log::info('[CIERRE DE HORAS] Analizando entradas de usuario con id: '.$id_usuario);
        $entrada = self::GetEntradaActiva($id_usuario);
        $turnosDiarios = self::GetTurnosDiariosUsuario($id_usuario);

        if($entrada == null){  // Caso en el que no hay entradas activas           

           $entrada = self::BuildEntradaDefault($id_usuario);
           if($entrada == null)  // Este caso es cuando el usuario no tiene turnos
            return;
        
            //TODO  Revisar si hay una entrada marcada como hecha, de ser afirmativo, habria que evitar analisis de horas y c0ntemplar si hay que anlizar entradas posteriores en caso de multiturno

            // Caso en el que no hay entradas activas, y todas sus posibles entradas ya habian sido cerradas previamente
            if(self::GetEntradasRegistradasDia($id_usuario) == count($turnosDiarios))
                return;
            
           $entrada->hora_salida = $entrada->hora_entrada;
        }

        #region FACTORIZADO Settear Status y Ajustar Horas Realizadas
        $entrada = self::SetStatusAjustarHorasRealizadas($entrada, StatusType::NO_MARCO_SALIDA);
        #endregion

        self::$entradaRepository->save($entrada);
        


        // Buscar todas las posibles entradas que puede haber despues de la ultima activa y setearles su hora programada asi como la hora de salida la cual es la del cierre

        // ITERAR POR EL NUMERO DE VECES DE ENTRADAS POSIBLES POSTERIOR A LA ENTRADA ANALIZADA LAS POSIBILES SIGUIENTES ENTRADAS
        // MARCAR COMO ENTRADAS NO REALIZADAS
        if(count($turnosDiarios) > 1){
           // TODO Iniciar el for loop segun la cantidad de entradas hechas previamente -> utilizando la logica de contar entradas
            for($i = self::GetEntradasRegistradasDia($id_usuario); $i < count($turnosDiarios); $i++){
                
                $entradaVacia = self::BuildEntradaDefault($id_usuario);
                if($entradaVacia == null)
                    continue;
                
                $entradaVacia->hora_salida = $entradaVacia->hora_entrada;
                $entradaVacia->id_status = StatusType::NO_CUMPLIO_ENTRADA;
                self::$entradaRepository->save($entradaVacia);
            }

        }



    }

    public static function GetTurnosDiariosUsuario($id_usuario) {
        #region FINAL Obtener dia de la semana, horario del alumno y turnos diarios
        $diaActual = date('l');
        switch ($diaActual) {
            case 'Monday':
                $diaActual = DiasEnum::LUNES;
                break;
            case 'Tuesday':
                $diaActual = DiasEnum::MARTES;
                break;
            case 'Wednesday':
                $diaActual = DiasEnum::MIERCOLES;
                break;
            case 'Thursday':
                $diaActual = DiasEnum::JUEVES;
                break;
            case 'Friday':
                $diaActual = DiasEnum::VIERNES;
                break;
            case 'Saturday':
                $diaActual = DiasEnum::SABADO;
                break;
            case 'Sunday':
                $diaActual = DiasEnum::DOMINGO;
                break;
            default:
                throw new CustomException("Error al calcular el dáa de la semana");
                break;
        }

        $horarios = self::$horarioRepository->find(['id_usuario' => $id_usuario]);
        
        if($horarios != null)
            $turnosDiarios = self::$turnoDiarioRepository->findByHorarioAndDia($horarios[0]->id, $diaActual);
        else 
            $turnosDiarios = [];
        #endregion

        return $turnosDiarios;
    }

    private static function SetHorasEntrada($entrada, $turnoDiario){
        $result_entrada = $entrada;

        $fechaActual = self::getFechaActual();
        $horaActual = self::getHoraActual();

        $entrada->hora_entrada = self::getTimestamp($fechaActual, $horaActual);
        $entrada->horas_realizadas = 0;

        $entrada->hora_entrada_programada = self::getTimestamp($fechaActual, $turnoDiario->hora_entrada);
        $entrada->hora_salida_programada = self::getTimestamp($fechaActual, $turnoDiario->hora_salida);

        $entrada->horas_realizadas_programada = self::getDiffHoras($turnoDiario->hora_entrada, $turnoDiario->hora_salida);

        //Validar que solo editamos los atributos que queremos
        $result_entrada->hora_entrada = $entrada->hora_entrada;
        $result_entrada->horas_realizadas = $entrada->horas_realizadas;
        $result_entrada->hora_entrada_programada = $entrada->hora_entrada_programada;
        $result_entrada->horas_realizadas_programada = $entrada->horas_realizadas_programada;

        return $result_entrada;
    }

    public static function SetHorasSalidaYHorasRealizadas($entrada){
        $result_entrada = $entrada;
        #region FINAL Settear hora de salida y horas realizadas
        $fechaActual = self::getFechaActual();
        $horaActual = self::getHoraActual();

        $entrada->hora_salida = self::getTimestamp($fechaActual, $horaActual);
        $entrada->horas_realizadas = self::getDiffHoras($entrada->hora_entrada, $entrada->hora_salida);

        //Validar que solo editamos los atributos que queremos
        $result_entrada->hora_salida = $entrada->hora_salida;
        $result_entrada->horas_realizadas = $entrada->horas_realizadas;

        return $result_entrada;
        #endregion
    }

    public static function SetStatusAjustarHorasRealizadas($entrada, $satus_si_cerro_tarde){
        #region FINAL Settear Status y Ajustar Horas Realizadas
        $result_entrada = $entrada;

        //TODO: EL "toleranceRangeCheckout" Y EL "toleranceRangeOverTime" DEBERIAN SER PARAMETROS?
        if ($entrada->horas_realizadas < $entrada->horas_realizadas_programada &&
            ($entrada->horas_realizadas + self::$toleranceRangeCheckout) >= $entrada->horas_realizadas_programada){
                $entrada->horas_realizadas = $entrada->horas_realizadas_programada;
        }

        if ($entrada->horas_realizadas == 0) { $entrada->id_status = StatusType::NO_CUMPLIO_ENTRADA; }
        elseif ($entrada->horas_realizadas < $entrada->horas_realizadas_programada) { $entrada->id_status = StatusType::CUMPLIO_ENTRADA_INCOMPLETA; }
        else {

            //Obtenemos la suma de las horas realizadas y las horas programadas (excluyendo la entrada del dia actual)
            $suma_horas_realizadas = self::$entradaRepository->getHorasRealizadasByUsuario($entrada->id_usuario);
            $suma_horas_realizadas_programadas = self::$entradaRepository->getHorasRealizadasProgramadaByUsuario($entrada->id_usuario);

            //Revisamos si la persona tiene permiso para reponer horas (revisando la suma de "horas realizadas" con la suma de "horas realizadas programadas")
            if ($suma_horas_realizadas >= $suma_horas_realizadas_programadas){
                //Si no tiene permiso entra en este caso


                if ($entrada->horas_realizadas > ($entrada->horas_realizadas_programada + self::$toleranceRangeOverTime)){
                    //Si la persona cerro N minutos tarde, marcaremos su status segun el segundo parametro enviado
                    $entrada->id_status = $satus_si_cerro_tarde; //StatusType::NO_MARCO_SALIDA;
                }
                else{
                    $entrada->id_status = StatusType::CUMPLIO_ENTRADA;
                }

                //Setteamos las horas realizadas como las programadas, por si se llegaba a sobrepasar, para que no lo haga
                $entrada->horas_realizadas = $entrada->horas_realizadas_programada;
            }
            else if ( ($suma_horas_realizadas + $entrada->horas_realizadas) > ($suma_horas_realizadas_programadas + $entrada->horas_realizadas_programada)){
                //Si tiene permiso para reponer horas, pero hizo demasiadas horas, solo le daremos las horas que le faltaban por hacer

                $entrada->horas_realizadas = ($suma_horas_realizadas_programadas - $suma_horas_realizadas) + $entrada->horas_realizadas_programada;
                $entrada->id_status = StatusType::CUMPLIO_ENTRADA;
            }
            else{
                //Si no cumplió ningún caso, settearemos como que cumplió correctamente la entrada
                $entrada->id_status = StatusType::CUMPLIO_ENTRADA;
            }
        }

        //Validar que solo editamos los atributos que queremos
        $result_entrada->id_status = $entrada->id_status;
        $result_entrada->horas_realizadas = $entrada->horas_realizadas;

        return $result_entrada;
        #endregion
    }

    private static function getTimestamp($fecha, $hora){
        return $fecha . ' ' . $hora;
    }
    
    private static function getDiffHoras($hora_inicio, $hora_fin){
        $diff = date_diff(date_create($hora_inicio), date_create($hora_fin));
        $diffHoras = ($diff->d * 24) + $diff->h + ($diff->i / 60);
        return $diffHoras;
    }
    
    private static function getFechaActual(){
        return date('Y-m-d');
    }
    
    private static function getHoraActual(){
        return date('H:i:s');
    }

}

EntradaHelpers::init();