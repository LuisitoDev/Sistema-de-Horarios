<?php

namespace App\Http\Controllers;

use App\Exports\UsuariosExport;
use App\Imports\ExcelUsuariosImport;
use App\Imports\ExcelEntradasImport;
use App\Models\Administrador;
use App\Models\Dispositivo;
use App\Models\SolicitudDispositivo;
use App\Models\Usuario;
use Illuminate\Http\Request;
use App\Enums\StatusType;
use App\Enums\StatusResponseEnum;
use App\Exceptions\ExceptionHandler;
use DB;
use Throwable;
use Maatwebsite\Excel\Facades\Excel;
use App\Exceptions\CustomException;
use App\Exports\EntradasExport;
use App\Models\CicloEscolar;
use App\Models\Entrada;
use App\Models\Horario;
use App\Models\UsuarioPrograma;
use PDO;
use Log;
use Session;
use Illuminate\Support\Carbon;
use Exception;

class AdminController extends Controller
{
    public function __construct()
    {
        // $this->middleware('admin.in-host');
    }

    public function SetIdCicloEscolarSession(Request $request) {
        try {

            $Id_Ciclo_Escolar = $request->input('Id_Ciclo_Escolar');
            session(['Id_Ciclo_Escolar' => $Id_Ciclo_Escolar]);

            if ($Id_Ciclo_Escolar !== Session::get('Id_Ciclo_Escolar'))
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

    public function GetIdCicloEscolarSession() {
        return response()->json([
            "getSchoolCycle" => getSchoolCycle(),
        ]);
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

    public function Logout(Request $request) {
        $adminToken = session('adminToken');
        if($adminToken != null){
            $request->session()->invalidate();
            return redirect('/admin/login');
        }
        return redirect('/error');
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
            $admin = Administrador::where(['username' => $username, 'password' => $password])->first();

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


    public function GetCicloEscolar(Request $request) {
        try {


            $ciclosEscolares = CicloEscolar::orderBy('fecha_ingreso', 'DESC')->get();

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


    public function DeviceRequests($elements, $page, $selected_school_cycle) {
        try {
            // Obtener sesion de admin( Autentificacion de admin)



            // TODO devolver respuesta de que no hay permiso para acceder al endpoint

            $totalOfRequests = SolicitudDispositivo::all()->count();

            // $CicloEscolar = getSchoolCycle();
            $CicloEscolar = CicloEscolar::where('id', $selected_school_cycle)->first();

            $deviceRequest = SolicitudDispositivo::select(
                'solicitudes_dispositivos.id',
                'solicitudes_dispositivos.created_at',
                'direccion_mac_dispositivo',
                'solicitudes_dispositivos.id_usuario',
                'usuarios.nombre',
                'apellido_pat',
                'apellido_mat',
                'matricula',
                'correo_universitario',
                'fecha_creacion',
                'abreviacion',
                'servicios.nombre AS servicio_nombre')
            ->leftjoin('usuarios', 'solicitudes_dispositivos.id_usuario', '=', 'usuarios.id')
            ->leftjoin('carreras', 'usuarios.id_carrera', '=', 'carreras.id')
            ->leftjoin('usuarios_servicios', 'usuarios_servicios.id_usuario', '=', 'usuarios.id')
            ->leftjoin('servicios', 'usuarios_servicios.id_servicio', '=', 'servicios.id')
            ->whereDate('solicitudes_dispositivos.created_at', '>=', $CicloEscolar->fecha_ingreso)
            ->whereDate('solicitudes_dispositivos.created_at', '<=', $CicloEscolar->fecha_salida)
            ->skip($page * $elements - $elements)
            ->take($elements)
            ->get();

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


    public function GetStudents($elements, $page, $search, $dayFrom, $dayTo) {
        try{

            // Obtener sesion de admin( Autentificacion de admin)

            $totalOfStudents = 0;

            if ($search == "null")
                $search = "";

            if ($dayFrom === "0")
                $dayFrom = null;

            if ($dayTo === "0")
                $dayTo = null;

            $totalOfStudents = Usuario::join('ciclo_escolar', 'ciclo_escolar.id', '=', 'usuarios.id_ciclo_escolar')
            ->when($search != "", function($query) use ($search){
                return $query->where(function ($query) use ($search) {
                    $query->where('usuarios.matricula', 'like', '%'. $search.'%')
                    ->orWhere('usuarios.nombre', 'like', '%'.$search.'%')
                    ->orWhere('usuarios.apellido_pat', 'like', '%'.$search.'%')
                    ->orWhere('usuarios.apellido_mat', 'like', '%'.$search.'%');
                });
            })
            ->when($dayFrom != null && $dayTo != null, function($query) use ($dayFrom, $dayTo){
                return $query->where(function ($query) use ($dayFrom, $dayTo) {
                    $query->whereRaw(
                        "(ciclo_escolar.fecha_ingreso <= ? && ciclo_escolar.fecha_salida >= ?)
                        OR  (ciclo_escolar.fecha_ingreso <= ? && ciclo_escolar.fecha_salida >= ?)
                        OR  (ciclo_escolar.fecha_ingreso >= ? && ciclo_escolar.fecha_salida <= ?)",
                        [$dayFrom, $dayFrom, $dayTo, $dayTo, $dayFrom, $dayTo]);
                });
            })
            ->count();


            $students = Usuario::select('usuarios.id',
                'usuarios.nombre',
                'usuarios.apellido_pat',
                'usuarios.apellido_mat',
                'usuarios.matricula',
                'correo_universitario',
                'abreviacion',
                'servicios.nombre as servicio_nombre')
                ->leftjoin('carreras', 'usuarios.id_carrera','=', 'carreras.id')
                ->leftjoin('usuarios_servicios', 'usuarios_servicios.id_usuario', '=', 'usuarios.id')
                ->leftjoin('servicios', 'usuarios_servicios.id_servicio', '=', 'servicios.id')
                ->join('ciclo_escolar', 'ciclo_escolar.id', '=', 'usuarios.id_ciclo_escolar')
                ->when($search != "", function($query) use ($search){
                    return $query->where(function ($query) use ($search) {
                        $query->where('usuarios.matricula', 'like', '%'. $search.'%')
                        ->orWhere('usuarios.nombre', 'like', '%'.$search.'%')
                        ->orWhere('usuarios.apellido_pat', 'like', '%'.$search.'%')
                        ->orWhere('usuarios.apellido_mat', 'like', '%'.$search.'%');
                    });
                })
                ->when($dayFrom != null && $dayTo != null, function($query) use ($dayFrom, $dayTo){
                    return $query->where(function ($query) use ($dayFrom, $dayTo) {
                        $query->whereRaw(
                            "(ciclo_escolar.fecha_ingreso <= ? && ciclo_escolar.fecha_salida >= ?)
                            OR  (ciclo_escolar.fecha_ingreso <= ? && ciclo_escolar.fecha_salida >= ?)
                            OR  (ciclo_escolar.fecha_ingreso >= ? && ciclo_escolar.fecha_salida <= ?)",
                            [$dayFrom, $dayFrom, $dayTo, $dayTo, $dayFrom, $dayTo]);
                    });
                })
                ->skip($page * $elements - $elements)
                ->take($elements)->get();


            $numberOfPages = 0;
            if(count($students) != 0){
                $numberOfPages = ceil($totalOfStudents / $elements);
            }

            return response([
                "students" => $students,
                "totalPages" => $numberOfPages
            ])->header('Content-Type','application/json');
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


    public function GetStudentsChecks($elements, $page, $search, $dayFrom, $dayTo) {
        try{

            if ($dayFrom === "0")
                $dayFrom = null;

            if ($dayTo === "0")
                $dayTo = null;


            if ($search == "null")
                $search = "";

            $totalOfStudents = Usuario::join('ciclo_escolar', 'ciclo_escolar.id', '=', 'usuarios.id_ciclo_escolar')
            ->when($search != "", function($query) use ($search){
                return $query->where(function ($query) use ($search) {
                    $query->where('usuarios.matricula', 'like', '%'. $search.'%')
                    ->orWhere('usuarios.nombre', 'like', '%'.$search.'%')
                    ->orWhere('usuarios.apellido_pat', 'like', '%'.$search.'%')
                    ->orWhere('usuarios.apellido_mat', 'like', '%'.$search.'%');
                });
            })
            ->when($dayFrom != null && $dayTo != null, function($query) use ($dayFrom, $dayTo){
                return $query->where(function ($query) use ($dayFrom, $dayTo) {
                    $query->whereRaw(
                        "(ciclo_escolar.fecha_ingreso <= ? && ciclo_escolar.fecha_salida >= ?)
                        OR  (ciclo_escolar.fecha_ingreso <= ? && ciclo_escolar.fecha_salida >= ?)
                        OR  (ciclo_escolar.fecha_ingreso >= ? && ciclo_escolar.fecha_salida <= ?)",
                        [$dayFrom, $dayFrom, $dayTo, $dayTo, $dayFrom, $dayTo]);
                });
            })
            ->count();



            $students = Usuario::select('usuarios.id', 'usuarios.matricula', 'usuarios.nombre', 'usuarios.apellido_pat', 'usuarios.apellido_mat',
                'usuarios.correo_universitario', 'usuarios.id_carrera','carreras.abreviacion','servicios.nombre as servicio_nombre',
                DB::raw("count(entradas.id_usuario) as entradas"), DB::raw("sum(entradas.horas_realizadas_programada) - sum(entradas.horas_realizadas) as horas_pendientes")
                )
                ->leftjoin('carreras', 'usuarios.id_carrera','=', 'carreras.id')
                ->join('ciclo_escolar', 'ciclo_escolar.id', '=', 'usuarios.id_ciclo_escolar')
                ->leftjoin('usuarios_servicios', 'usuarios_servicios.id_usuario', '=', 'usuarios.id')
                ->leftjoin('servicios', 'usuarios_servicios.id_servicio', '=', 'servicios.id')
                ->leftJoin('entradas', function ($leftJoin) use ($dayFrom, $dayTo){
                    $leftJoin->on('usuarios.id', '=', 'entradas.id_usuario')
                    ->where('entradas.id_status', '!=', StatusType::TRABAJANDO)
                    ->whereDate('entradas.hora_entrada_programada', '>=', $dayFrom)
                    ->whereDate('entradas.hora_entrada_programada', '<=', $dayTo);
                })
                ->groupBy('usuarios.id', 'usuarios.matricula', 'usuarios.nombre', 'usuarios.apellido_pat', 'usuarios.apellido_mat',
                'usuarios.correo_universitario', 'usuarios.id_carrera','carreras.abreviacion', 'servicio_nombre')
                ->when($search != "", function($query) use ($search){
                    return $query->where(function ($query) use ($search) {
                        $query->where('usuarios.matricula', 'like', '%'. $search.'%')
                        ->orWhere('usuarios.nombre', 'like', '%'.$search.'%')
                        ->orWhere('usuarios.apellido_pat', 'like', '%'.$search.'%')
                        ->orWhere('usuarios.apellido_mat', 'like', '%'.$search.'%');
                    });
                })
                ->when($dayFrom != null && $dayTo != null, function($query) use ($dayFrom, $dayTo){
                    return $query->where(function ($query) use ($dayFrom, $dayTo) {
                        $query->whereRaw(
                            "(ciclo_escolar.fecha_ingreso <= ? && ciclo_escolar.fecha_salida >= ?)
                            OR  (ciclo_escolar.fecha_ingreso <= ? && ciclo_escolar.fecha_salida >= ?)
                            OR  (ciclo_escolar.fecha_ingreso >= ? && ciclo_escolar.fecha_salida <= ?)",
                            [$dayFrom, $dayFrom, $dayTo, $dayTo, $dayFrom, $dayTo]);
                    });
                })
                ->skip($page * $elements - $elements)
                ->take($elements)->get();

            $numberOfPages = 0;
            if(count($students) != 0){
                $numberOfPages = ceil($totalOfStudents / $elements);
            }

            return response([
                "students" => $students,
                "totalPages" => $numberOfPages,
            ])->header('Content-Type','application/json');
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

    public function DeleteStudent($tuition) {
        try{

            $student = Usuario::where('matricula', $tuition)->first();

            if($student == null)
                throw new CustomException('No se ha podido eliminar porque no se ha encontrado un alumno con esa matricula');

            $student->delete();


            return response()->json([
                "STATUS" => StatusResponseEnum::SUCCESS,
                'MESSAGE' => 'El alumno fue eliminado exitosamente',
            ]);
        }
        catch(CustomException $exception) {
            return ExceptionHandler::respondExceptionJSON($exception);
        }
        catch(Throwable $exception) {
            if(!env('DEBUGGER'))
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

            $requestId = $request->input('id');
            // Obtener la informacion de la solicitud
            // Revisar que el dipositivo no este vinculado a algun otro usuario
            $requestInfo = SolicitudDispositivo::firstWhere('id', $requestId);



            if($requestInfo == null) {
               throw new CustomException("This request id was not found");
            }



            $deviceRegistered = Dispositivo::firstWhere('direccion_mac', $requestInfo->direccion_mac_dispositivo);
            if($deviceRegistered != null) {
               throw new CustomException("Este dispositivo ya esta vinculado a un usuario");
            }


            // Eliminar registro de la tabla de solicitudes y agregarlo a la tabla de dispositivos



            Dispositivo::create([
                'direccion_mac' => $requestInfo->direccion_mac_dispositivo,
                'id_usuario' => $requestInfo->id_usuario
            ]);


            SolicitudDispositivo::where('id', $requestId)->delete();


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

            $requestId = $request->input('id');
            // Obtener la informacion de la solicitud
            // Revisar que el dipositivo no este vinculado a algun otro usuario
            $requestInfo = SolicitudDispositivo::firstWhere('id', $requestId);



            if($requestInfo == null) {
               throw new CustomException("This request id was not found");
            }



            SolicitudDispositivo::where('id', $requestId)->delete();



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

    function ImportStudentsData(Request $request)
    {
        try{
            $this->validate($request, [
                'select_file'  => 'required|mimes:xls,xlsx'
            ]);

            $file = $request->file('select_file');

            Excel::import(new ExcelUsuariosImport, $file);

            return response()->json([
                "STATUS" => StatusResponseEnum::SUCCESS,
                'MESSAGE' => 'Alumnos importados correctamente'
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

    public function ExportStudentsData($dayFrom, $dayTo)
    {
        return (new UsuariosExport($dayFrom, $dayTo))->download('usuarios_export.xlsx');
    }

    public function ExportStudentsHoursData($dayFrom, $dayTo)
    {
        return (new EntradasExport($dayFrom, $dayTo))->download('usuarios_export.xlsx');
    }

    function ImportStudentsHoursData(Request $request)
    {
        try{
            $this->validate($request, [
                'select_file'  => 'required|mimes:xls,xlsx'
            ]);

            $file = $request->file('select_file');

            Excel::import(new ExcelEntradasImport, $file);

            return response()->json([
                "STATUS" => StatusResponseEnum::SUCCESS,
                'MESSAGE' => 'Entradas de alumnos importadas correctamente'
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

    function DeleteOldData() {
        try {

            // TODO Delete data with lifespan old or equal to 3 years
            $date = Carbon::now()->subYears(3);

            $usuarios = Usuario::whereDate('created_at', '<=', $date)->withTrashed()->get();

            foreach($usuarios as $usuario) {
                Entrada::where('id_usuario', $usuario->id)->forceDelete();
                Horario::where('id_usuario', $usuario->id)->forceDelete();
                Dispositivo::where('id_usuario', $usuario->id)->forceDelete();
                UsuarioPrograma::where('id_usuario', $usuario->id)->forceDelete();
            }

            Usuario::whereDate('created_at', '<=', $date)->withTrashed()->forceDelete();

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


}
