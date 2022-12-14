<?php


namespace App\Imports;

use App\Enums\DiasEnum;
use App\Models\Carrera;
use App\Models\Programa;
use App\Models\Servicio;
use App\Models\Usuario;
use App\Models\CicloEscolar;
use App\Models\UsuarioPrograma;
use App\Models\UsuarioServicio;
use Illuminate\Validation\Rule;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;

use Throwable;
use Exception;
use Log;

class UsuariosImport implements ToCollection, WithHeadingRow, WithBatchInserts, WithChunkReading
{
    private $carreras;
    private $servicios;
    private $programas;
    private $ciclo_escolar;

    public function __construct()
    {
        $this->carreras = Carrera::pluck('id', 'abreviacion');
        $this->servicios = Servicio::pluck('id', 'nombre');
        $this->programas = Programa::pluck('id', 'nombre');
        $this->ciclo_escolar = CicloEscolar::orderBy('fecha_ingreso', 'DESC')->first();
    }

    public function collection(Collection $rows)
    {
        foreach ($rows as $row)
        {
            $usuario_actual = Usuario::select('*')->where('correo_universitario', $row['correo_uanl'])->first();
            $id_usuario = null;

            if ($usuario_actual == null){
                $usuario_subido = Usuario::create([
                    'nombre'                => $row['nombre_asesor'],
                    'apellido_pat'          => $row['apellido_paterno'],
                    'apellido_mat'          => $row['apellido_materno'],
                    'matricula'             => $row['matricula'],
                    'correo_universitario'  => $row['correo_uanl'],
                    'id_carrera'            => $this->carreras[$row['carrera']],
                    'id_ciclo_escolar'      => $this->ciclo_escolar->id,
                    'id_rotacion'           => $row['id_rotacion'],
                ]);

                $id_usuario = $usuario_subido->id;
            }
            else{
                $id_usuario = $usuario_actual->id;
            }

            $usuario_servicio = UsuarioServicio::select('*')->where('id_usuario', $id_usuario)->first();

            if ($usuario_servicio == null){
                UsuarioServicio::create([
                    'id_usuario'            => $id_usuario,
                    'id_servicio'           => $this->servicios[$row['servicio']]
                ]);
            }
            else if ($usuario_servicio->id_servicio !== $this->servicios[$row['servicio']]){
                UsuarioServicio::create([
                    'id_usuario'            => $id_usuario,
                    'id_servicio'           => $this->servicios[$row['servicio']]
                ]);
            }

            if ($row['servicio'] == "Servicio Social"){
                $usuario_programa = UsuarioPrograma::select('*')->where('id_usuario', $id_usuario)->first();

                if ($usuario_programa == null){
                    UsuarioPrograma::create([
                        'id_usuario'            => $id_usuario,
                        'id_programa'           => $this->programas[$row['programa']]
                    ]);
                }
                else if ($usuario_programa->id_programa !== $this->programas[$row['programa']]){
                    UsuarioPrograma::create([
                        'id_usuario'            => $id_usuario,
                        'id_programa'           => $this->programas[$row['programa']]
                    ]);
                }

            }


        }
    }

    //TODO: VALIDAR QUE CADA CAMPO EXISTA Y CUENTE CON LOS REQUISITOS NECESARIOS
    public function rules(): array
    {
        return [

        ];
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
