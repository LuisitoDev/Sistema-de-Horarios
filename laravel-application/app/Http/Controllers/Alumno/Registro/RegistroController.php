<?php

namespace App\Http\Controllers\Alumno\Registro;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

//Repositories
use App\Repositories\Dispositivo\DispositivoRepository;
use App\Repositories\SolicitudDispositivo\SolicitudDispositivoRepository;
use App\Repositories\Usuario\UsuarioRepository;

//Exception
use Exception;
use Throwable;
use App\Exceptions\CustomException;
use App\Exceptions\ExceptionHandler;

//Utils
use App\Enums\StatusResponseEnum;

class RegistroController extends Controller
{

    private $solicitudDispositivoRepository;
    private $usuarioRepository;
    private $dispositivoRepository;

    public function __construct(
        SolicitudDispositivoRepository $solicitudDispositivoRepository,
        UsuarioRepository $usuarioRepository,
        DispositivoRepository $dispositivoRepository) 
    {
        $this->solicitudDispositivoRepository = $solicitudDispositivoRepository;
        $this->usuarioRepository = $usuarioRepository;
        $this->dispositivoRepository = $dispositivoRepository;
    }

    public function Register(Request $request) {
        try{
            if($request->ip() == $request->server('SERVER_ADDR')){
                throw new Exception ("No se ha podido procesar tu solicitud");
            }

            if(session('userToken')) {
                return redirect('/');
            }

            return view('asesor.registro');
        }
        catch (Throwable $exception) {
            return ExceptionHandler::respondExceptionView($exception);
        }
    }
    
    //Pagina Agregar Dispositivo
    public function AddDevice(Request $request) {

        $mac = get_client_mac($request->ip());

        if($mac == null){
            return redirect('/error')->with(['errorMessage' => 'No se ha podido procesar tu solicitud']);
        }

        if(session('userToken')){
            return redirect('/');
        }

        $previousDevice = $this->solicitudDispositivoRepository->findFirst(['direccion_mac_dispositivo' => $mac]);
        
        if($previousDevice){
            return view('asesor.solicitud-pendiente', compact('previousDevice'));
        }

        $mac = get_client_mac($request->ip());
        if($mac == null){
            return redirect('/error')->with(['errorMessage'=> 'No se ha podido procesar tu solicitud.']);
        }

        $device = $this->dispositivoRepository->findFirst(['dispositivos.direccion_mac' => $mac]);

        if($device){
            $request->session()->invalidate();
            session(['userToken' => true]);
            return redirect('/');
        }

        return view('asesor.agregar-dispositivo');
    }

    public function SignIn(Request $request) {
        try{
            // Mandar la solicitud para que el administrador aprueba el dispositivo

            $request->validate([
                'email' => 'required'
            ]);

            // Revisar si el correo universitario existe
            $email = $request->input('email');
            $user = $this->usuarioRepository->findFirst(["correo_universitario" => $email]);

            if(!$user){
                throw new CustomException("The email is not registered", 401);
            }

            $macAddress = get_client_mac($request->ip());

            if($macAddress != null) {

                $previousDevice = $this->solicitudDispositivoRepository->findFirst(["direccion_mac_dispositivo" => $macAddress]);

                if($previousDevice){
                    throw new CustomException("There was a previous device request for this device waiting to be aproved", 403);
                }

                // TODO Revisar si ese dispositivo ya esta vinculado con algun otro usuario

                $deviceRegistered = $this->dispositivoRepository->findFirst(["direccion_mac" => $macAddress]);

                if($deviceRegistered) {
                    throw new CustomException("This device is already linked to an user", 403);
                }

                $this->solicitudDispositivoRepository->create(
                    [
                        'direccion_mac_dispositivo' => $macAddress,
                        'id_usuario' => $user->id
                    ]
                );

                return response()->json([
                    "STATUS" => StatusResponseEnum::SUCCESS,
                    'MESSAGE' => 'THE REQUEST WAS SENDED CORRECTLY'
                ], 200);

            }
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
