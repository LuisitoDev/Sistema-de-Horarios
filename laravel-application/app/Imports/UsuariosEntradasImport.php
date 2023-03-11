<?php


namespace App\Imports;

use App\Models\Entrada;
use App\Models\Usuario;

use App\Enums\StatusType;
use Illuminate\Validation\Rule;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToArray;
use App\Exceptions\CustomException;
use App\Http\Controllers\Alumno\CargaHoras\EntradaController;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;

use Illuminate\Support\Facades\DB;

class UsuariosEntradasImport implements ToCollection, WithHeadingRow, WithBatchInserts, WithChunkReading
{

    private $arrayUsers = [];

    public function __construct()
    {
    }

    public function collection(Collection $rows)
    {
        $entradas = [];

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
                $user = Usuario::where(DB::raw('lower(correo_universitario)'), 'like', '%' . strtolower($email) . '%')->first();

                if ($user !== null)
                    array_push($this->arrayUsers, $user);
            }
            else
                $user = $userFound;

            if ($user === null)
                throw new CustomException("Correo del Usuario \"". $email . "\" no encontrado al importar sus entradas", 404);

            $hora_inicio_turno = $row['hora_inicio_turno'];
            $hora_finalizacion_turno = $row['hora_finalizacion_turno'];

            $hora_entrada = $row['hora_entrada'];
            $hora_salida = $row['hora_salida'];

            if ($hora_inicio_turno === null)
                // throw new CustomException("La hora de inicio de turno del usuario con correo: ". $email . " es nula ", 403);
                continue;

            if ($hora_finalizacion_turno === null)
                // throw new CustomException("La hora de fin de turno del usuario con correo: ". $email . " es nula ", 403);
                continue;

            $horas_realizadas =  round($row['horas_trabajadas'], 3);
            $horas_realizadas_programada = round($row['horas_programadas'], 3);

            $id_status = null;

            //TODO: ESTE CODIGO ESTA REPETIDO CON EL ARCHIVO "EntradaController::RegistarHoraSalida"
            //Si la persona cerro un poco antes su entrada, pero entra dentro del rango de tolerancia aceptado, le damos sus horas completas
            if ($horas_realizadas < $horas_realizadas_programada &&
            ($horas_realizadas + EntradaController::$toleranceRangeCheckout) >= $horas_realizadas_programada){
                $horas_realizadas = $horas_realizadas_programada;
            }

            if ($horas_realizadas == 0) { $id_status = StatusType::NO_CUMPLIO_ENTRADA; }
            elseif ($horas_realizadas < $horas_realizadas_programada) { $id_status = StatusType::CUMPLIO_ENTRADA_INCOMPLETA; }
            else {

                //Obtenemos la suma de las horas realizadas y las horas programadas (excluyendo la entrada del dia actual)
                $suma_horas_realizadas = Entrada::where([['id_usuario', "=", $user->id], ['id_status', "!=", StatusType::TRABAJANDO]])->sum('horas_realizadas');
                $suma_horas_realizadas_programadas = Entrada::where([['id_usuario', "=", $user->id], ['id_status', "!=", StatusType::TRABAJANDO]])->sum('horas_realizadas_programada');

                //Revisamos si la persona tiene permiso para reponer horas (revisando la suma de "horas realizadas" con la suma de "horas realizadas programadas")
                if ($suma_horas_realizadas >= $suma_horas_realizadas_programadas){
                    //Si no tiene permiso entra en este caso

                    if ($horas_realizadas > ($horas_realizadas_programada + EntradaController::$toleranceRangeOverTime)){
                        //Si la persona cerro 30 minutos tarde, marcaremos su status como que cerro tarde
                        $id_status = StatusType::CERRO_TARDE;
                    }
                    else{
                        $id_status = StatusType::CUMPLIO_ENTRADA;
                    }

                    //Setteamos las horas realizadas como las programadas, por si se llegaba a sobrepasar, para que no lo haga
                    $horas_realizadas = $horas_realizadas_programada;
                }
                else if ( ($suma_horas_realizadas + $horas_realizadas) > ($suma_horas_realizadas_programadas + $horas_realizadas_programada)){
                    //Si tiene permiso para reponer horas, pero hizo demasiadas horas, solo le daremos las horas que le faltaban por hacer

                    $horas_realizadas = ($suma_horas_realizadas_programadas - $suma_horas_realizadas) + $horas_realizadas_programada;
                    $id_status = StatusType::CUMPLIO_ENTRADA;
                }
                else{
                    //Si no cumplió ningún caso, settearemos como que cumplió correctamente la entrada
                    $id_status = StatusType::CUMPLIO_ENTRADA;
                }
            }

            Entrada::create([
                'hora_entrada_programada' => $this->convertExcelDateToDate($hora_inicio_turno),
                'hora_salida_programada' => $this->convertExcelDateToDate($hora_finalizacion_turno),
                'horas_realizadas_programada' => $horas_realizadas_programada,
                'hora_entrada' => $this->convertExcelDateToDate($hora_entrada),
                'hora_salida' => $this->convertExcelDateToDate($hora_salida),
                'horas_realizadas' => $horas_realizadas,
                'reporte_diario' => '',
                'id_status' => $id_status,
                'id_usuario' => $user->id
            ]);

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
