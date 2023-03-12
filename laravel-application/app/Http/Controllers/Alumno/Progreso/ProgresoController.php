<?php

namespace App\Http\Controllers\Alumno\Progreso;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

//Repositories
use App\Repositories\Entrada\EntradaRepository;
use App\Repositories\Servicio\ServicioRepository;

//Exception
use Exception;
use Throwable;
use App\Exceptions\CustomException;
use App\Exceptions\ExceptionHandler;

//Utils
use App\Enums\StatusResponseEnum;
use Illuminate\Support\Facades\Log;

class ProgresoController extends Controller
{
    private $entradaRepository;
    private $servicioRepository;

    public function __construct(
        EntradaRepository $entradaRepository,
        ServicioRepository $servicioRepository) 
    {
        $this->entradaRepository = $entradaRepository;
        $this->servicioRepository = $servicioRepository;
    }

    public function MyProgress(Request $request, $elements, $dayFrom, $dayTo){
        try{
            if (!env("DEBUGGER"))
                $id_usuario = getUserByMacAddress($request->ip());
            else
                $id_usuario = env("ID_USER_DEBUGG");

            if ($dayFrom === "0")
                $dayFrom = null;
            
            if ($dayFrom === "0")
                $dayTo = null;

            $entradas = $this->entradaRepository->findByUserBetweenDates($id_usuario, $dayFrom, $dayTo);
            $cantPaginas = ceil( count($entradas) / $elements );

            $horas_realizadas = $this->entradaRepository->getSumHorasRealizadasByUsuario($id_usuario);

            $horas_totales = $this->servicioRepository->getHorasTotalesByServicioUsuario($id_usuario);

            $horas_pendientes = $this->entradaRepository->getHorasPendientesByUsuario($id_usuario);

            return response()->json([
                "cantidad_paginas" => $cantPaginas,
                "horas_realizadas" => $horas_realizadas,
                "horas_servicio" => $horas_totales,
                "horas_pendientes" => $horas_pendientes
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

    public function MyHours(Request $request, $elements, $page, $dayFrom, $dayTo){
        try{
            if (!env("DEBUGGER"))
                $id_usuario = getUserByMacAddress($request->ip());
            else
                $id_usuario = env("ID_USER_DEBUGG");

            $pagination = ($page - 1) * $elements;

            if ($dayFrom === "0")
                $dayFrom = null;
            
            if ($dayFrom === "0")
                $dayTo = null;

            $entradas = $this->entradaRepository->findByUserBetweenDates($id_usuario, $dayFrom, $dayTo, $pagination, $elements);

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
}
