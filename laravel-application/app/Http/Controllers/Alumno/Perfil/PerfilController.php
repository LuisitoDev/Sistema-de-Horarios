<?php

namespace App\Http\Controllers\Alumno\Perfil;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

//Repositories
use App\Repositories\Usuario\UsuarioRepository;

//Exception
use Exception;
use Throwable;
use App\Exceptions\CustomException;
use App\Exceptions\ExceptionHandler;

//Utils
use App\Enums\StatusResponseEnum;
use Intervention\Image\Facades\Image;

class PerfilController extends Controller
{
    private $usuarioRepository;

    public function __construct(
        UsuarioRepository $usuarioRepository,) 
    {
        $this->usuarioRepository = $usuarioRepository;
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
                      $usuario = $this->usuarioRepository->findFirst(["matricula" => $tuition]);
  
                      if(!$usuario)
                          throw new CustomException('No existe un usuario con esa matricula', 404);

                    $id_usuario = $usuario->id;
                }
            }
            else
                $id_usuario = env("ID_USER_DEBUGG");

            //Se hace el select correspondiente haciendo un join usando los fundamentos aprendidos en Modelos de
            //administracion de datos y Alto Volumen de Datos
            //TODO:TEST PETICION
            $usuario = $this->usuarioRepository->findProfileById($id_usuario);
            
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

            $usuario = $this->usuarioRepository->findFirst(["id" => $id_usuario]);
            // TODO: Comprimir imagen y convertir a JPEG
            $decodedImage = base64_decode($request->input('imagen'));
            // $compressImage = imagejpeg($decodedImage, null, 20);
            $resizedImage = Image::make($decodedImage)->resize(260, 260)->encode('jpg', 75);
            $usuario->imagen= $resizedImage;
            $this->usuarioRepository->save($usuario);
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
}
