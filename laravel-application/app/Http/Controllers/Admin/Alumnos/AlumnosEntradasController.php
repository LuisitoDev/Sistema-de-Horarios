<?php

namespace App\Http\Controllers\Admin\Alumnos;


use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

//Excel
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\EntradasExport;
use App\Imports\ExcelEntradasImport;

//Repositories
use App\Repositories\Usuario\UsuarioRepository;

//Exception
use Exception;
use Throwable;
use App\Exceptions\CustomException;
use App\Exceptions\ExceptionHandler;

//Utils
use App\Enums\StatusResponseEnum;

class AlumnosEntradasController extends Controller
{
   
    private $usuarioRepository;

    public function __construct(
        UsuarioRepository $usuarioRepository)
    {
        $this->usuarioRepository = $usuarioRepository;
        // $this->middleware('admin.in-host');
    }

   
    public function GetStudentsChecks($elements, $page, $search, $dayFrom, $dayTo) {
        try{

            if ($dayFrom === "0")
                $dayFrom = null;

            if ($dayTo === "0")
                $dayTo = null;


            if ($search == "null")
                $search = "";

            $allStudents = $this->usuarioRepository->findByFieldBetweenDates($search, $dayFrom, $dayTo);

            if ($allStudents === null)
                $totalOfStudents = 0;
            else 
                $totalOfStudents = count($allStudents);

            $students = $this->usuarioRepository->findStudentsChecks($search, $dayFrom, $dayTo, $page, $elements);

            $numberOfPages = 0;
            if($students !== null){
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

    public function ExportStudentsHoursData($dayFrom, $dayTo)
    {
        return (new EntradasExport($dayFrom, $dayTo))->download('usuarios_export.xlsx');
    }

}
