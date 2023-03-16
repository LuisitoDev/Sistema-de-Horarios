<?php


namespace App\Imports;

use App\Enums\DiasEnum;
use App\Exceptions\CustomException;
use App\Repositories\Horario\HorarioRepository;
use App\Repositories\TurnoDiario\TurnoDiarioRepository;
use App\Repositories\Usuario\UsuarioRepository;
use Illuminate\Validation\Rule;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use PhpParser\Node\Stmt\Break_;

class HorariosImport implements ToCollection, WithHeadingRow, WithBatchInserts, WithChunkReading
{
    private $arrayUsers = [];
    private $usuarioRepository;
    private $horarioRepository;
    private $turnoDiarioRepository;

    public function __construct()
    {
        $this->usuarioRepository = new UsuarioRepository;
        $this->horarioRepository = new HorarioRepository;
        $this->turnoDiarioRepository = new TurnoDiarioRepository;
    }

    public function collection(Collection $rows)
    {
        foreach ($rows as $row)
        {

            $email = $row['correo_uanl'];

            if ($email === null)
                break;

            $user = null;
            $userFound = $this->findUserByEmail($email);

            if ($userFound === false){
                $user = $this->usuarioRepository->findByCorreo($email);

                if ($user !== null)
                    array_push($this->arrayUsers, $user);
            }
            else
                $user = $userFound;

            if ($user === null)
                throw new CustomException("Correo del Usuario \"". $email . "\" no encontrado al importar su horario", 404);


            $id_horario = 0;
            $horario = $this->horarioRepository->getByUsuario($user->id);

            if ($horario !== null){
                $id_horario = $horario->id;
            }
            else{
                $horario_subido = $this->horarioRepository->create($user->id);

                $id_horario = $horario_subido->id;
            }

            if ($id_horario === 0)
                throw new CustomException("Hubo un problema al encontrar el horario del usuario con correo: ". $email, 404);

            $lunes_entrada = $row['lunes_entrada'];
            $lunes_salida = $row['lunes_salida'];
            if (!($lunes_entrada === null && $lunes_salida === null)){

                $this->checkTurnBlocked($email, $id_horario, $lunes_entrada, $lunes_salida, DiasEnum::LUNES);

                $this->turnoDiarioRepository->create($lunes_entrada, $lunes_salida, DiasEnum::LUNES, $id_horario);
            }

            $martes_entrada = $row['martes_entrada'];
            $martes_salida = $row['martes_salida'];
            if (!($martes_entrada === null && $martes_salida === null)){

                $this->checkTurnBlocked($email, $id_horario, $martes_entrada, $martes_salida, DiasEnum::MARTES);

                $this->turnoDiarioRepository->create($martes_entrada, $martes_salida, DiasEnum::MARTES, $id_horario);
            }

            $miercoles_entrada = $row['miercoles_entrada'];
            $miercoles_salida = $row['miercoles_salida'];
            if (!($miercoles_entrada === null && $miercoles_salida === null)){

                $this->checkTurnBlocked($email, $id_horario, $miercoles_entrada, $miercoles_salida, DiasEnum::MIERCOLES);

                $this->turnoDiarioRepository->create($miercoles_entrada, $miercoles_salida, DiasEnum::MIERCOLES, $id_horario);
            }

            $jueves_entrada = $row['jueves_entrada'];
            $jueves_salida = $row['jueves_salida'];
            if (!($jueves_entrada === null && $jueves_salida === null)){

                $this->checkTurnBlocked($email, $id_horario, $jueves_entrada, $jueves_salida, DiasEnum::JUEVES);

                $this->turnoDiarioRepository->create($jueves_entrada, $jueves_salida, DiasEnum::JUEVES, $id_horario);
            }

            $viernes_entrada = $row['viernes_entrada'];
            $viernes_salida = $row['viernes_salida'];
            if (!($viernes_entrada === null && $viernes_salida === null)){

                $this->checkTurnBlocked($email, $id_horario, $viernes_entrada, $viernes_salida, DiasEnum::VIERNES);

                $this->turnoDiarioRepository->create($viernes_entrada, $viernes_salida, DiasEnum::VIERNES, $id_horario);
            }
        }
    }

    //TODO: VALIDAR QUE CADA CAMPO EXISTA Y CUENTE CON LOS REQUISITOS NECESARIOS
    public function rules(): array
    {
        return [

        ];
    }

    //TODO: ESTA FUNCION ESTA REPETIDA EN EL IMPORT "UsuariosEntradasImport", DEBERÍAMOS HACERLA GLOBAL?
    function findUserByEmail($email){
        foreach ( $this->arrayUsers as $user ) {
            if ( $user->correo_universitario === $email ) {
                return $user;
            }
        }

        return false;
    }

    function checkTurnBlocked($email, $id_horario, $hora_entrada, $hora_salida, $dia_turno){

        //TODO: VALIDAR QUE LOS TURNOS DEBEN ESTAR DENTRO DEL RANGO DE HORAS HABILES

        $dia_nombre = "";
        switch ($dia_turno) {
            case DiasEnum::LUNES:
                $dia_nombre = "lunes";
                break;
            case DiasEnum::MARTES:
                $dia_nombre = "martes";
                break;
            case DiasEnum::MIERCOLES:
                $dia_nombre = "miercoles";
                break;
            case DiasEnum::JUEVES:
                $dia_nombre = "jueves";
                break;
            case DiasEnum::VIERNES:
                $dia_nombre = "viernes";
                break;

            default:
                throw new CustomException("No se especifico correctamente el dia del turno", 403);
                break;
            }

        if ($hora_entrada == null)
            throw new CustomException("El turno del dia ".$dia_nombre." del usuario con correo: ". $email . " no es valido porque la hora de entrada es nula", 403);

        if ($hora_salida == null)
            throw new CustomException("El turno del dia ".$dia_nombre." del usuario con correo: ". $email . " no es valido porque la hora de salida es nula", 403);

        if ($hora_entrada == $hora_salida)
            throw new CustomException("El turno del dia ".$dia_nombre." de ". $hora_entrada ."-". $hora_salida ." del usuario con correo: ". $email . " no es valido", 403);

        $t1 = strtotime($hora_entrada);
        $t2 = strtotime($hora_salida);
        if ($t1 >= $t2)
            throw new CustomException("El turno del dia ".$dia_nombre." de ". $hora_entrada ."-". $hora_salida ." del usuario con correo: ". $email . " no es valido", 403);


        $turnoBloqueado = $this->turnoDiarioRepository->checkTurnoBloqueado($hora_entrada, $hora_salida, $dia_turno, $id_horario); 

        if ($turnoBloqueado !== null)
            throw new CustomException("El turno del dia ".$dia_nombre." de ". $hora_entrada ."-". $hora_salida ." del usuario con correo: ". $email . " está bloqueado por otro turno", 403);

    }

    public function batchSize(): int
    {
        return 3;
    }

    public function chunkSize(): int
    {
        return 3;
    }
}
