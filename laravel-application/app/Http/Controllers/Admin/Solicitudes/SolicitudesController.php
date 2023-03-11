<?php

namespace App\Http\Controllers\Admin\Solicitudes;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;


//Repositories
use App\Repositories\CicloEscolar\CicloEscolarRepository;
use App\Repositories\Dispositivo\DispositivoRepository;
use App\Repositories\SolicitudDispositivo\SolicitudDispositivoRepository;


//Exception
use Exception;
use Throwable;
use App\Exceptions\CustomException;
use App\Exceptions\ExceptionHandler;


//Utils
use App\Enums\StatusResponseEnum;
use Illuminate\Support\Facades\Log;

class SolicitudesController extends Controller
{
    private $cicloEscolarRepository;
    private $solicitudDispositivoRepository;
    private $dispositivoRepository;

    public function __construct(
        CicloEscolarRepository $cicloEscolarRepository,
        SolicitudDispositivoRepository $solicitudDispositivoRepository,
        DispositivoRepository $dispositivoRepository)
    {
        $this->cicloEscolarRepository = $cicloEscolarRepository;
        $this->solicitudDispositivoRepository = $solicitudDispositivoRepository;
        $this->dispositivoRepository = $dispositivoRepository;
        // $this->middleware('admin.in-host');
    }

    public function DeviceRequests($elements, $page, $selected_school_cycle) {
        try {
            // Obtener sesion de admin( Autentificacion de admin)

            // TODO devolver respuesta de que no hay permiso para acceder al endpoint

            $totalOfRequests = count($this->solicitudDispositivoRepository->findAll());

            $CicloEscolar = $this->cicloEscolarRepository->findById($selected_school_cycle);

            $deviceRequest = $this->solicitudDispositivoRepository->findInDates(
                $CicloEscolar->fecha_ingreso,
                $CicloEscolar->fecha_salida,
                $page, $elements);

            

            $numberOfPages = 0;
            if(count($deviceRequest) != 0){
                $numberOfPages = ceil($totalOfRequests / $elements);
            }

            return response([
                "requests" => $deviceRequest,
                "totalPages" => $numberOfPages
            ])->header('Content-Type','application/json');
        }catch(CustomException $exception){
            return ExceptionHandler::respondExceptionJSON($exception);
        }catch(Throwable $exception){
            if (!env("DEBUGGER"))
            $exception = new Exception("", 0, $exception);

            return ExceptionHandler::respondExceptionJSON($exception);
        }

    }

    public function AcceptRequest(Request $request){
        try {


            // TODO devolver respuesta de que no hay permiso para acceder al endpoint


            // Obtener en el parametro el ID de la solicitud
            $request->validate([
                'id' => 'required'
            ]);

            $id_solicitud_dispositivo = $request->input('id');
            // Obtener la informacion de la solicitud
            // Revisar que el dipositivo no este vinculado a algun otro usuario
            $requestInfo = $this->solicitudDispositivoRepository->findById($id_solicitud_dispositivo);

            if($requestInfo == null) {
               throw new CustomException("This request id was not found");
            }

            $deviceRegistered = $this->dispositivoRepository->findFirst(['direccion_mac' => $requestInfo->direccion_mac_dispositivo]);

            if($deviceRegistered != null) {
               throw new CustomException("Este dispositivo ya esta vinculado a un usuario");
            }

            // Eliminar registro de la tabla de solicitudes y agregarlo a la tabla de dispositivos

            $this->dispositivoRepository->create(
                [
                    'direccion_mac' => $requestInfo->direccion_mac_dispositivo,
                    'id_usuario' => $requestInfo->id_usuario
                ]
            );

            $this->solicitudDispositivoRepository->deleteById($id_solicitud_dispositivo);

            return response()->json([
                "STATUS" => StatusResponseEnum::SUCCESS,
                'MESSAGE' => 'La solicitud fue aceptada exitosamente'
            ]);
        }
        catch(CustomException $exception){
            return ExceptionHandler::respondExceptionJSON($exception);
        }
        catch(Throwable $exception){
            if (!env("DEBUGGER"))
                $exception = new Exception("", 0, $exception);

            return ExceptionHandler::respondExceptionJSON($exception);
        }



    }

    public function RejectRequest(Request $request){
        try {



            // Obtener en el parametro el ID de la solicitud


            // Eliminar registro de la tabla de solicitudes

            $request->validate([
                'id' => 'required'
            ]);

            $id_solicitud_dispositivo = $request->input('id');
            // Obtener la informacion de la solicitud
            // Revisar que el dipositivo no este vinculado a algun otro usuario
            $requestInfo = $this->solicitudDispositivoRepository->findById($id_solicitud_dispositivo);


            if($requestInfo == null) {
               throw new CustomException("This request id was not found");
            }

            $this->solicitudDispositivoRepository->deleteById($id_solicitud_dispositivo);

            return response()->json([
                "STATUS" => StatusResponseEnum::SUCCESS,
                'MESSAGE' => 'The request was rejected succesfully'
            ]);
        } catch(CustomException $exception){
            return ExceptionHandler::respondExceptionJSON($exception);
        } catch (Throwable $exception){
            if (!env("DEBUGGER"))
                $exception = new Exception("", 0, $exception);

            return ExceptionHandler::respondExceptionJSON($exception);
        }




    }

}
