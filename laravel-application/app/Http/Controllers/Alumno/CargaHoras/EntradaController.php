<?php

namespace App\Http\Controllers\Alumno\CargaHoras;

use App\Http\Controllers\Controller;
use App\Helpers\EntradaHelpers;
use Illuminate\Http\Request;
use App\Enums\StatusType;
use Illuminate\Support\Carbon;
use App\Enums\DiasEnum;
use App\Exceptions\ExceptionHandler;
use App\Exceptions\CustomException;
use App\Repositories\Usuario\UsuarioRepository;
use App\Repositories\Entrada\EntradaRepository;
use App\Repositories\Horario\HorarioRepository;
use App\Repositories\TurnoDiario\TurnoDiarioRepository;
use Throwable;
use Exception;

class EntradaController extends Controller
{

    public static $toleranceRangeCheckout = 0.02; //1 Mins
    public static $toleranceRangeOverTime = 0.5; //5 Mins
    private $usuarioRepository;
    private $entradaRepository;
    private $horarioRepository;
    private $turnoDiarioRepository;

    public function __construct(
        UsuarioRepository $usuarioRepository,
        EntradaRepository $entradaRepository,
        HorarioRepository $horarioRepository,
        TurnoDiarioRepository $turnoDiarioRepository
    ) {
        $this->usuarioRepository = $usuarioRepository;
        $this->entradaRepository = $entradaRepository;
        $this->turnoDiarioRepository = $turnoDiarioRepository;
        $this->horarioRepository = $horarioRepository;
    }

    public function RegistarHoraEntrada(Request $request) {
        try{
            if (!env("DEBUGGER")){
               
            
                $id_usuario = getUserByMacAddress($request->ip());

                // Revisar si mando la matricula como parametro para tomarla encuenta y de ahi partir
                if($request->input('tuition')){
              
                    // Revisar caso del admin
                    $adminToken = session('adminToken');

                    if($adminToken == null)
                        throw new CustomException('No se cuenta con los permisos de administrador para realizar esta accion');
                    
                
                    $tuition = $request->input('tuition');
                      
                    // Obtener el id del usuario con su matricula
                    
                    $usuario = $this->usuarioRepository->findFirst(["matricula" => $tuition]);

                    if(!$usuario)
                        throw new CustomException('No existe un usuario con esa matricula', 404);

                    $id_usuario = $usuario->id;

                }
                

            } else
                $id_usuario = env("ID_USER_DEBUGG");

            $entradaPrevia = $this->entradaRepository->findByUsuarioAndStatus($id_usuario, StatusType::TRABAJANDO);

            if($entradaPrevia != null){
                throw new CustomException("Ya hay una entrada abierta", 403);
            }

            $turnos = EntradaHelpers::GetTurnosDiarios($id_usuario)->count();
            $entradas_diarias = count($this->entradaRepository->findByUsuarioAndHoraEntrada($id_usuario, Carbon::today()));

            if($entradas_diarias >= $turnos){
                throw new CustomException("Ya has marcado la(s) entrada(s) del dia de hoy", 403);
            }

            $entrada = EntradaHelpers::BuildEntradaDefault($id_usuario);
            $entrada->id_status = StatusType::TRABAJANDO;

            $this->entradaRepository->save($entrada);

            return $entrada;
        }
        catch (CustomException $exception) {
            return ExceptionHandler::respondExceptionJSON($exception);
        }
        catch (Throwable $exception) {
            if (!env("DEBUGGER"))
                $exception = new Exception("", 0, $exception);

            return ExceptionHandler::respondExceptionJSON($exception);
        }
    }

    public function RegistarHoraSalida(Request $request) {
        try{
            if (!env("DEBUGGER")){
                $id_usuario = getUserByMacAddress($request->ip());

                if($request->input('tuition')){
              
                    // Revisar caso del admin
                    $adminToken = session('adminToken');

                    if($adminToken == null)
                        throw new CustomException('No se cuenta con los permisos de administrador para realizar esta accion');
                    
                
                    $tuition = $request->input('tuition');
                      
                    // Obtener el id del usuario con su matricula
                    $usuario = $this->usuarioRepository->findFirst(["matricula" => $tuition]);

                    if(!$usuario)
                        throw new CustomException('No existe un usuario con esa matricula', 404);

                    $id_usuario = $usuario->id;   
                }
                

            }
            else
                $id_usuario = env("ID_USER_DEBUGG");

            $entrada = $this->entradaRepository->findByUsuarioAndStatus($id_usuario, StatusType::TRABAJANDO);
            
            if($entrada == null) {
                throw new CustomException("No puedas marcar salida porque no existe una entrada activa");
            }

            $fechaActual = date('Y-m-d');
            $horaActual = date('H:i:s');

            $entrada->hora_salida = $fechaActual . ' ' . $horaActual;
            $diff = date_diff(date_create($entrada->hora_entrada), date_create($entrada->hora_salida));
            $entrada->horas_realizadas = ($diff->d * 24) + $diff->h + ($diff->i / 60);

            //Si la persona cerro un poco antes su entrada, pero entra dentro del rango de tolerancia aceptado, le damos sus horas completas
            if ($entrada->horas_realizadas < $entrada->horas_realizadas_programada &&
                ($entrada->horas_realizadas + self::$toleranceRangeCheckout) >= $entrada->horas_realizadas_programada){
                    $entrada->horas_realizadas = $entrada->horas_realizadas_programada;
            }

            if ($entrada->horas_realizadas == 0) { $entrada->id_status = StatusType::NO_CUMPLIO_ENTRADA; }
            elseif ($entrada->horas_realizadas < $entrada->horas_realizadas_programada) { $entrada->id_status = StatusType::CUMPLIO_ENTRADA_INCOMPLETA; }
            else {

                //Obtenemos la suma de las horas realizadas y las horas programadas (excluyendo la entrada del dia actual)
                $suma_horas_realizadas = $this->entradaRepository->getHorasRealizadasByUsuario($id_usuario);
                $suma_horas_realizadas_programadas = $this->entradaRepository->getHorasRealizadasProgramadaByUsuario($id_usuario);

                //Revisamos si la persona tiene permiso para reponer horas (revisando la suma de "horas realizadas" con la suma de "horas realizadas programadas")
                if ($suma_horas_realizadas >= $suma_horas_realizadas_programadas){
                    //Si no tiene permiso entra en este caso

                    if ($entrada->horas_realizadas > ($entrada->horas_realizadas_programada + self::$toleranceRangeOverTime)){
                        //Si la persona cerro 30 minutos tarde, marcaremos su status como que cerro tarde
                        $entrada->id_status = StatusType::CERRO_TARDE;
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

            $this->entradaRepository->save($entrada);
            return $entrada;
        }
        catch (CustomException $exception) {
            return ExceptionHandler::respondExceptionJSON($exception);
        }
        catch (Throwable $exception) {
            if (!env("DEBUGGER"))
                $exception = new Exception("", 0, $exception);

            return ExceptionHandler::respondExceptionJSON($exception);
        }
    }

    public function ObtenerEntradaDiaria(Request $request) {
        try{
            if (!env("DEBUGGER")){
                $id_usuario = getUserByMacAddress($request->ip());

                if($request->input('tuition')){
              
                    // Revisar caso del admin
                    $adminToken = session('adminToken');

                    if($adminToken == null)
                        throw new CustomException('No se cuenta con los permisos de administrador para realizar esta accion');
                    
                
                    $tuition = $request->input('tuition');
                      
                    // Obtener el id del usuario con su matricula
                    $usuario = $this->usuarioRepository->findFirst(["matricula" => $tuition]);

                    if(!$usuario)
                        throw new CustomException('No existe un usuario con esa matricula', 404);

                    $id_usuario = $usuario->id;   
                }
                

            }
            else
                $id_usuario = env("ID_USER_DEBUGG");

            //TODO: ESTE CODIGO SE REPITE CON "EntradasHelpers::BuildEntradaDefault()", optimizar en una funcion
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
                default:
                    throw new CustomException("No hay entradas del dia actual");
                    break;
            }
            
            $horarios = $this->horarioRepository->find(['id_usuario' => $id_usuario]);

            $turnosDiarios = $this->turnoDiarioRepository->findByHorarioAndDia($horarios->id, $diaActual);

            $entradas = $this->entradaRepository->findByUsuarioAndHoraEntradaProgramada($id_usuario, Carbon::today());

            if (count($entradas) == 0){
                $ultimaEntrada = $this->entradaRepository->findUltimaEntradaByUsuario($id_usuario);

                if ($ultimaEntrada->id_status == StatusType::TRABAJANDO)
                    $entradas[0] = $ultimaEntrada;
            }


            return response()->json([
                "turnosDiarios" => $turnosDiarios,
                "entradas" => $entradas
            ]);
        }
        catch (CustomException $exception) {
            return ExceptionHandler::respondExceptionJSON($exception);
        }
        catch (Throwable $exception) {
            if (!env("DEBUGGER"))
                $exception = new Exception("", 0, $exception);

            return ExceptionHandler::respondExceptionJSON($exception);
        }
    }
}
