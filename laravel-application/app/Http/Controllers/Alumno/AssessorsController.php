<?php

namespace App\Http\Controllers\Alumno;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

//Exception
use Exception;
use Throwable;
use App\Exceptions\CustomException;
use App\Exceptions\ExceptionHandler;

//Utils
use App\Enums\StatusResponseEnum;

class AssessorsController extends Controller
{

    public function Index() {
        return view('asesor.home-asesor');
    }

    public function GetDeviceRequest() {


    }

    public function Clock() {
        return view('assessors.example');
    }

    //TEST
    public function Progress(){
        return view('asesor.progreso');
    }

    public function Hour(){
        return view('asesor.carga-horas');
    }

    
    public function Perfil(){
        return view('asesor.perfil-alumno');
    }


}
