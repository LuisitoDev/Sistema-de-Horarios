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
use Throwable;
use Exception;
use Illuminate\Support\Facades\Log;

class EntradaController extends Controller
{
    private $usuarioRepository;
    private $entradaRepository;

    public function __construct(
        UsuarioRepository $usuarioRepository,
        EntradaRepository $entradaRepository,
    ) {
        $this->usuarioRepository = $usuarioRepository;
        $this->entradaRepository = $entradaRepository;
    }

    public function RegistarHoraEntrada(Request $request) {
        try{

            $id_usuario = self::getIdUsuario($request);

            $entradaPrevia = $this->entradaRepository->findByUsuarioAndStatus($id_usuario, StatusType::TRABAJANDO);

            if($entradaPrevia != null){
                throw new CustomException("Ya hay una entrada abierta", 403);
            }

            $turnos = count(EntradaHelpers::GetTurnosDiariosUsuario($id_usuario));
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
            $id_usuario = self::getIdUsuario($request);

            $entrada = $this->entradaRepository->findByUsuarioAndStatus($id_usuario, StatusType::TRABAJANDO);
            
            if($entrada == null) {
                throw new CustomException("No puedas marcar salida porque no existe una entrada activa");
            }

            #region FACTORIZADO Settear hora de salida y horas realizadas
            $entrada = EntradaHelpers::SetHorasSalidaYHorasRealizadas($entrada);
            #endregion

            #region FACTORIZADO Settear Status y Ajustar Horas Realizadas
            $entrada = EntradaHelpers::SetStatusAjustarHorasRealizadas($entrada, StatusType::CERRO_TARDE);
            #endregion

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
            $id_usuario = self::getIdUsuario($request);

            #region FACTORIZADO Obtener dia de la semana, horario del alumno y turnos diarios
            $turnosDiarios = EntradaHelpers::GetTurnosDiariosUsuario($id_usuario);
            #endregion
            
            #region N/A Obtener entradas del dia de hoy
            $entradas = $this->entradaRepository->findByUsuarioAndHoraEntradaProgramada($id_usuario, Carbon::today());
            #endregion

            if ($entradas === null){
                $ultimaEntrada = $this->entradaRepository->findUltimaEntradaByUsuario($id_usuario);

                if ($ultimaEntrada->id_status == StatusType::TRABAJANDO)
                    $entradas[0] = $ultimaEntrada;
                else
                    $entradas = [];
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

    private function getIdUsuario(Request $request){
        $id_usuario = null;

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
            
        if ($id_usuario === null)
            throw new CustomException('No fue encontrado el usuario', 404);
    }
}
