<?php

namespace App\Exceptions;
use App\Enums\StatusResponseEnum;
use Illuminate\Http\Response;

use Log;

class ExceptionHandler
{
    static private function rollbackTransaction(){

    }

    static private function reportLogs($exception){
        Log::info($exception);
    }

    static public function respondExceptionView($exception){
        self::rollbackTransaction();
        self::reportLogs();

        return redirect('/error')->with(['errorMessage' => $exception->getMessage()]);

    }

    static public function respondExceptionJSON($exception){
        self::rollbackTransaction();
        self::reportLogs($exception);

        $code = $exception->getCode();
        $message = $exception->getMessage();

        if (!self::is_valid_http_status($code))
            $code = 403;

        if (!env("DEBUGGER")){
            if ($message === "")
                $message = "Hubo un error en el servidor";

            return response()->json([
                'STATUS' => StatusResponseEnum::ERROR,
                'MESSAGE' => $message
            ],  $code);
        }
        else{

            if ($exception->getPrevious() !== null){
                $exception = $exception->getPrevious();
            }

            return response()->json([
                'STATUS' => StatusResponseEnum::ERROR,
                'MESSAGE' => $exception->getMessage(),
                'LINE' => $exception->getLine(),
                'FILE' => $exception->getFile()
            ],  $code);
        }
    }

    static private function is_valid_http_status($code) : bool {
        if (!is_numeric($code))
            return false;

        return array_key_exists($code, Response::$statusTexts);
    }

}
