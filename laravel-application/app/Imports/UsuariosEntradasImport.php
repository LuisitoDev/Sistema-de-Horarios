<?php


namespace App\Imports;

use App\Enums\StatusType;
use Illuminate\Validation\Rule;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToArray;
use App\Exceptions\CustomException;
use App\Helpers\EntradaHelpers;
use App\Http\Controllers\Alumno\CargaHoras\EntradaController;
use App\Repositories\Entrada\EntradaRepository;
use App\Repositories\Usuario\UsuarioRepository;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;

use Illuminate\Support\Facades\DB;

class UsuariosEntradasImport implements ToCollection, WithHeadingRow, WithBatchInserts, WithChunkReading
{

    private $arrayUsers = [];
    private $entradaRepository;
    private $usuarioRepository;

    public function __construct()
    {
        $this->entradaRepository = new EntradaRepository;
        $this->usuarioRepository = new UsuarioRepository;
    }

    public function collection(Collection $rows)
    {
        $entrada = $this->entradaRepository->getEntradaModel();

        foreach ($rows as $row)
        {
            if (!isset($row['correo']))
                break;

            $email = $row['correo'];

            if ($email === null || $email === "#N/A")
                // throw new CustomException("El correo ingresado es nulo o no valido", 404);
                continue;

            $user = null;
            $userFound = $this->findUserByEmail($email);

            if ($userFound === false){
                $user = $this->usuarioRepository->findByCorreo($email, false);

                if ($user !== null)
                    array_push($this->arrayUsers, $user);
            }
            else
                $user = $userFound;

            if ($user === null)
                throw new CustomException("Correo del Usuario \"". $email . "\" no encontrado al importar sus entradas", 404);

            if ($row['hora_inicio_turno'] === null)
                // throw new CustomException("La hora de inicio de turno del usuario con correo: ". $email . " es nula ", 403);
                continue;
            
            if ($row['hora_finalizacion_turno'] === null)
                // throw new CustomException("La hora de fin de turno del usuario con correo: ". $email . " es nula ", 403);
                continue;
            
            //TODO: PENDIENTE TESTEAR
            $entrada->hora_entrada_programada = $this->convertExcelDateToDate($row['hora_inicio_turno']);
            $entrada->hora_salida_programada = $this->convertExcelDateToDate($row['hora_finalizacion_turno']);

            $entrada->hora_entrada = $this->convertExcelDateToDate($row['hora_entrada']);
            $entrada->hora_salida = $this->convertExcelDateToDate($row['hora_salida']);

            $entrada->horas_realizadas =  round($row['horas_trabajadas'], 3);
            $entrada->horas_realizadas_programada = round($row['horas_programadas'], 3);

            #region FACTORIZADO Settear Status y Ajustar Horas Realizadas
            $entrada = EntradaHelpers::SetStatusAjustarHorasRealizadas($entrada, StatusType::CERRO_TARDE);
            #endregion

            $entrada->id_usuario = $user->id;
            $entrada->reporte_diario = "";

            $resultado = $this->entradaRepository->save($entrada);
        }

    }

    private function convertExcelDateToDate($excel_date){
        if ($excel_date == null)
            return null;

        $excel_date = round($excel_date, 5);
        $unix_date = ($excel_date - 25569) * 86400;
        return gmdate("Y-m-d h:i:00", ceil($unix_date));
    }

    function findUserByEmail($email){
        foreach ( $this->arrayUsers as $user ) {
            if ( $user->correo_universitario === $email ) {
                return $user;
            }
        }

        return false;
    }

    //TODO: VALIDAR QUE CADA CAMPO EXISTA Y CUENTE CON LOS REQUISITOS NECESARIOS
    public function rules(): array
    {
        return [

        ];
    }

    public function batchSize(): int
    {
        return 1000;
    }

    public function chunkSize(): int
    {
        return 1000;
    }
}
