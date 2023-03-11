<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ErrorController extends Controller
{
    //TODO: IMPLEMENTAR MANEJO DE EXEPCIONES (ESPECIFICAR ERROR Y MARCAR EN LOGS)
    public function Handle(){
        return view('error');
    }
}
