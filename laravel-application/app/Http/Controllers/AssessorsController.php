<?php

namespace App\Http\Controllers;

use App\Models\Dispositivo;
use App\Models\Entrada;
use App\Models\SolicitudDispositivo;
use App\Models\Usuario;
use Illuminate\Http\Request;
use App\Enums\StatusType;
use App\Exceptions\ExceptionHandler;
use App\Exceptions\CustomException;
use App\Enums\StatusResponseEnum;
use App\Models\Servicio;
use DB;
use Throwable;
use Image;
use Exception;
use Maatwebsite\Excel\Facades\Excel;

class AssessorsController extends Controller
{
    public function Index() {
        return view('asesor.home-asesor');
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

    public function AddDevice(Request $request) {

        $mac = get_client_mac($request->ip());

        if($mac == null){
            return redirect('/error')->with(['errorMessage' => 'No se ha podido procesar tu solicitud']);
        }

        if(session('userToken')){
            return redirect('/');
        }



        $previousDevice = SolicitudDispositivo::select('solicitudes_dispositivos.id as id_solicitud', 'usuarios.correo_universitario')->leftjoin('usuarios', 'id_usuario', 'usuarios.id')->firstWhere('direccion_mac_dispositivo', $mac);

        if($previousDevice != null){
            return view('asesor.solicitud-pendiente', compact('previousDevice'));
        }




        $mac = get_client_mac($request->ip());
        if($mac == null){
            return redirect('/error')->with(['errorMessage'=> 'No se ha podido procesar tu solicitud.']);
        }


        $device = Dispositivo::join('usuarios', 'dispositivos.id_usuario', '=', 'usuarios.id')->where('dispositivos.direccion_mac', '=', $mac)->first();

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
            $user = Usuario::firstWhere('correo_universitario', $email);

            if(!$user){
                throw new CustomException("The email is not registered", 401);
            }

            $macAddress = get_client_mac($request->ip());

            if($macAddress != null) {


                $previousDevice = SolicitudDispositivo::where('direccion_mac_dispositivo', $macAddress)->first();

                if($previousDevice != null){
                    throw new CustomException("There was a previous device request for this device  waiting to be aproved", 403);
                }


                // TODO Revisar si ese dispositivo ya esta vinculado con algun otro usuario
                $deviceRegistered = Dispositivo::firstWhere('direccion_mac', $macAddress);
                if($deviceRegistered != null) {
                    throw new CustomException("This device is already linked to an user", 403);
                }


                SolicitudDispositivo::create([
                    'direccion_mac_dispositivo' => $macAddress,
                    'id_usuario' => $user->id
                ]);

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

    public function GetDeviceRequest() {


    }

    public function Clock() {
        return view('assessors.example');
    }


    public function MyHours(Request $request, $elements, $page, $dayFrom, $dayTo){
        try{
            if (!env("DEBUGGER"))
                $id_usuario = getUserByMacAddress($request->ip());
            else
                $id_usuario = env("ID_USER_DEBUGG");

            $pagination = ($page - 1) * $elements;

            $dayFrom === "0" ?  $dayFrom = null : $dayFrom = $dayFrom ." 00:00:00";

            $dayTo === "0" ?  $dayTo = null : $dayTo =  $dayTo ." 23:59:59";

            $entradas = Entrada::whereRaw(
                "
                id_usuario = ?
                AND     IF(? is null, 1, hora_entrada_programada >= ?)
                AND     IF(? is null, 1, hora_entrada_programada <=  ?)",
                [
                    $id_usuario,
                    $dayFrom,
                    $dayFrom,
                    $dayTo,
                    $dayTo
                ])->offset($pagination)->limit($elements)->orderBy('hora_entrada_programada', 'DESC')->get();


            return response()->json([
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
    public function Profile(Request $request){
        try {
            if (!env("DEBUGGER")){
                $id_usuario = getUserByMacAddress($request->ip());

                if($request->input('tuition')){
                      // Revisar caso del admin
                      $adminToken = session('adminToken');

                      if($adminToken == null)
                          throw new CustomException('No se cuenta con los permisos de administrador para realizar esta accion');
                      
                  
                      $tuition = $request->input('tuition');
                        
                      // Obtener el id del usuario con su matricula
                      $id_usuario = Usuario::where('matricula', $tuition)->first()->id;
  
                      if($id_usuario == null)
                          throw new CustomException('No existe un usuario con esa matricula', 404);
                }
            }
            else
                $id_usuario = env("ID_USER_DEBUGG");

            //Se hace el select correspondiente haciendo un join usando los fundamentos aprendidos en Modelos de
            //administracion de datos y Alto Volumen de Datos
            $usuario=Usuario::select('usuarios.matricula','usuarios.apellido_mat','usuarios.apellido_pat','usuarios.imagen','usuarios.correo_universitario as correo',
            'usuarios.nombre as nombre','carreras.nombre as carrera','programas.nombre as programa'
            ,'dispositivos.direccion_mac','servicios.nombre as servicio', 'carreras.abreviacion')
            ->leftjoin('dispositivos','dispositivos.id_usuario','=','usuarios.id')
            ->leftJoin('usuarios_programas','usuarios_programas.id_usuario','=','usuarios.id')
            ->leftJoin('programas','programas.id','=','usuarios_programas.id_programa')
            ->join('carreras','carreras.id','=','usuarios.id_carrera')
            ->leftjoin('usuarios_servicios', 'usuarios_servicios.id_usuario', '=', 'usuarios.id')
            ->leftjoin('servicios', 'usuarios_servicios.id_servicio', '=', 'servicios.id')
            ->where('usuarios.id', '=', $id_usuario)
            ->first();
            $usuario['imagen']=base64_encode($usuario['imagen']);
            //Aplicamos un poco del ENGINE de construccion de json implementado por Derek J Cortes
            return response()->json(["usuario"=>$usuario],200);
        }catch(CustomException $exception){
            return ExceptionHandler::respondExceptionJSON($exception);
        }catch(Throwable $exception) {
            if (!env("DEBUGGER"))
                $exception = new Exception("", 0, $exception);

            return ExceptionHandler::respondExceptionJSON($exception);
        }

    }
    public function UpdateProfilePicture(Request $request){
        try {
            if (!env('DEBUGGER'))
                $id_usuario = getUserByMacAddress($request->ip());
            else
                $id_usuario = env("ID_USER_DEBUGG");

            $usuario=Usuario::where('usuarios.id', '=', $id_usuario)
            ->first();
            // TODO: Comprimir imagen y convertir a JPEG
            $decodedImage = base64_decode($request->input('imagen'));
            // $compressImage = imagejpeg($decodedImage, null, 20);
            $resizedImage = Image::make($decodedImage)->resize(260, 260)->encode('jpg', 75);
            $usuario->imagen= $resizedImage;
            $usuario->save();
            return response()->json([
                "STATUS" => StatusResponseEnum::SUCCESS,
                'MESSAGE' =>$request->input('imagen')
            ]);
        } catch(CustomException $exception){
            return ExceptionHandler::respondExceptionJSON($exception);
        } catch(Throwable $exception) {
            if (!env("DEBUGGER"))
                $exception = new Exception("", 0, $exception);

            return ExceptionHandler::respondExceptionJSON($exception);
        }


    }
    public function MyProgress(Request $request, $elements, $dayFrom, $dayTo){
        try{
            if (!env("DEBUGGER"))
                $id_usuario = getUserByMacAddress($request->ip());
            else
                $id_usuario = env("ID_USER_DEBUGG");

            $dayFrom === "0" ?  $dayFrom = null : $dayFrom = $dayFrom ." 00:00:00";

            $dayTo === "0" ?  $dayTo = null : $dayTo =  $dayTo ." 23:59:59";

            $cantPaginas = ceil(Entrada::whereRaw(
                "
                id_usuario = ?
                AND     IF(? is null, 1, hora_entrada_programada >= ?)
                AND     IF(? is null, 1, hora_entrada_programada <=  ?)",
                [
                    $id_usuario,
                    $dayFrom,
                    $dayFrom,
                    $dayTo,
                    $dayTo
                ])->get()->count() / $elements);

            $horas_realizadas = Entrada::where('id_usuario', "=", $id_usuario)
                ->get()
                ->sum('horas_realizadas');

            $horas_totales = Servicio::join('usuarios_servicios', 'usuarios_servicios.id_servicio', '=', 'servicios.id')
                ->join('usuarios', 'usuarios_servicios.id_usuario', '=', 'usuarios.id')
                ->where('usuarios.id', '=', $id_usuario)->sum('horas_totales');

            $entradas = Entrada::select(DB::raw('sum(entradas.horas_realizadas_programada) - sum(entradas.horas_realizadas) as horas_pendientes'))
                ->where('entradas.id_usuario', "=", $id_usuario)
                ->where('entradas..id_status', "!=", StatusType::TRABAJANDO)
                ->first();

            return response()->json([
                "cantidad_paginas" => $cantPaginas,
                "horas_realizadas" => $horas_realizadas,
                "horas_servicio" => $horas_totales,
                "horas_pendientes" => $entradas->horas_pendientes
            ], 200);
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


    //TEST
    public function Progress(){
        return view('asesor.progreso');
    }

    public function Hour(){
        return view('asesor.carga-horas');
    }

}
