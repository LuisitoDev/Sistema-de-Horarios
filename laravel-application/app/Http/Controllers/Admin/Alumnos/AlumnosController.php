<?php

namespace App\Http\Controllers\Admin\Alumnos;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

//Excel
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\UsuariosExport;
use App\Imports\ExcelUsuariosImport;

//Repositories
use App\Repositories\Usuario\UsuarioRepository;

//Exception
use Exception;
use Throwable;
use App\Exceptions\CustomException;
use App\Exceptions\ExceptionHandler;

//Utils
use App\Enums\StatusResponseEnum;
use Illuminate\Support\Facades\Log;

class AlumnosController extends Controller
{
    private $usuarioRepository;

    public function __construct(
        UsuarioRepository $usuarioRepository)
    {
        $this->usuarioRepository = $usuarioRepository;
        // $this->middleware('admin.in-host');
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

            $allStudents = $this->usuarioRepository->findByFieldBetweenDates($search, $dayFrom, $dayTo);

            if ($allStudents === null)
                $totalOfStudents = 0;
            else 
                $totalOfStudents = count($allStudents);

            $students = $this->usuarioRepository->findByFieldBetweenDates($search, $dayFrom, $dayTo, $page, $elements);

            $numberOfPages = 0;
            if($students !== null){
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

    public function DeleteStudent($tuition) {
        try{

            $student = $this->usuarioRepository->findFirst(['matricula' => $tuition]);

            if(!$student)
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

}
