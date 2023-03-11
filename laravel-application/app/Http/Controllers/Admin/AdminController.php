<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

//Repositories
use App\Repositories\Admin\AdministradorRepository;
use App\Repositories\CicloEscolar\CicloEscolarRepository;

//Exception
use Exception;
use Throwable;
use App\Exceptions\CustomException;
use App\Exceptions\ExceptionHandler;


//Utils
use App\Enums\StatusResponseEnum;

class AdminController extends Controller
{
    private $administradorRepository;
    private $cicloEscolarRepository;

    public function __construct(
        AdministradorRepository $administradorRepository, 
        CicloEscolarRepository $cicloEscolarRepository)
    {
        $this->administradorRepository = $administradorRepository;
        $this->cicloEscolarRepository = $cicloEscolarRepository;
        // $this->middleware('admin.in-host');
    }
    
    public function Index() {
         // TODO: check if the admin is already authenticated and if not send it to admin/login

        $adminToken = session('adminToken');
        if($adminToken == null){
            return redirect('/admin/login');
        }

        return view('admin.home-admin');
    }

    public function Login() {
        $adminToken = session('adminToken');

        if($adminToken != null){
            return redirect('/admin');
        }
        return view('admin.login');
    }
    
    public function SignIn(Request $request) {
        

        $request->validate([
            'username' => 'required',
            'password' => 'required'
        ]);

        $username = $request->input('username');
        $password = $request->input('password');
        // CHECK IF THERES USER-PASSWORD COMBINATION
        try {
            $admin = $this->administradorRepository->find(['username' => $username, 'password' => $password]);

            if($admin == null){
                throw new CustomException("Usuario o contraseña incorrectos");
            }

            $request->session()->invalidate();
            session(['adminToken' => true]);

            return response()->json([
                "STATUS" => StatusResponseEnum::SUCCESS,
                'MESSAGE' => 'Admin authencation was succesfully'
            ]);
        } catch(CustomException $exception){
            return ExceptionHandler::respondExceptionJSON($exception);
        } catch(Throwable $exception){
            if (!env("DEBUGGER"))
                $exception = new Exception("", 0, $exception);

            return ExceptionHandler::respondExceptionJSON($exception);
        }
    }

    public function Logout(Request $request) {
        $adminToken = session('adminToken');
        if($adminToken != null){
            $request->session()->invalidate();
            return redirect('/admin/login');
        }
        return redirect('/error');
    }
 
    public function GetCicloEscolar(Request $request) {
        try {

            $ciclosEscolares = $this->cicloEscolarRepository->findAll();

            return response([
                "ciclosEscolares" => $ciclosEscolares,
            ])->header('Content-Type','application/json');


        }catch(CustomException $exception){
            return ExceptionHandler::respondExceptionJSON($exception);
        }catch(Throwable $exception){
            if (!env("DEBUGGER"))
            $exception = new Exception("", 0, $exception);

            return ExceptionHandler::respondExceptionJSON($exception);
        }

    }

    function DeleteOldData() {
        try {
            throw new CustomException("METHOD DeleteOldData DEPRECATED");
            //TODO: AL FINAL MEJOR LO MANEJAMOS DE OTRA MANERA
            // // TODO Delete data with lifespan old or equal to 3 years
            // $date = Carbon::now()->subYears(3);
            
            // $usuarios = Usuario::whereDate('created_at', '<=', $date)->withTrashed()->get();

            // foreach($usuarios as $usuario) {
            //     Entrada::where('id_usuario', $usuario->id)->forceDelete();
            //     Horario::where('id_usuario', $usuario->id)->forceDelete();
            //     Dispositivo::where('id_usuario', $usuario->id)->forceDelete();
            //     UsuarioPrograma::where('id_usuario', $usuario->id)->forceDelete();
            // }

            // Usuario::whereDate('created_at', '<=', $date)->withTrashed()->forceDelete();

            return response()->json([
                "STATUS" => StatusResponseEnum::SUCCESS,
                'MESSAGE' => 'Se han eliminado los registros antiguos'
            ]);

        }catch(CustomException $exception){
            return ExceptionHandler::respondExceptionJSON($exception);
        }catch(Throwable $exception) {
            if (!env("DEBUGGER"))
            $exception = new Exception("", 0, $exception);

            return ExceptionHandler::respondExceptionJSON($exception);
        }
    }

    // !DEPRECATED
    public function SetIdCicloEscolarSession(Request $request) {
        try {

            $Id_Ciclo_Escolar = $request->input('Id_Ciclo_Escolar');
            session(['Id_Ciclo_Escolar' => $Id_Ciclo_Escolar]);

            if ($Id_Ciclo_Escolar !== $request->session()->get('Id_Ciclo_Escolar'))
                throw new CustomException("No se pudo guardar correctamente el Id del Ciclo Escolar");

            return response()->json([
                "STATUS" => StatusResponseEnum::SUCCESS,
                'MESSAGE' => 'Ciclo Escolar actualizado correctamente'
            ]);

        }catch(CustomException $exception){
            return ExceptionHandler::respondExceptionJSON($exception);
        }catch(Throwable $exception){
            if (!env("DEBUGGER"))
            $exception = new Exception("", 0, $exception);

            return ExceptionHandler::respondExceptionJSON($exception);
        }

    }

    // !DEPRECATED
    public function GetIdCicloEscolarSession() {
        return response()->json([
            "getSchoolCycle" => getSchoolCycle(),
        ]);
    }

}
