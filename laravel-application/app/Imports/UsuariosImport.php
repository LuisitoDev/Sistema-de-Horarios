<?php


namespace App\Imports;

use App\Repositories\Admin\CarreraRepository;
use App\Repositories\Admin\ProgramaRepository;
use App\Repositories\CicloEscolar\CicloEscolarRepository;
use App\Repositories\Servicio\ServicioRepository;
use App\Repositories\Usuario\UsuarioRepository;
use App\Repositories\UsuarioPrograma\UsuarioProgramaRepository;
use App\Repositories\UsuarioServicio\UsuarioServicioRepository;
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


    private $usuarioRepository;
    private $programaRepository;
    private $carreraRepository;
    private $servicioRepository;
    private $cicloEscolarRepository;
    private $usuarioServicioRepository;
    private $usuarioProgramaRepository;

    public function __construct()
    {
        $this->usuarioRepository = new UsuarioRepository;
        $this->programaRepository = new ProgramaRepository;
        $this->carreraRepository = new CarreraRepository;
        $this->servicioRepository = new ServicioRepository;
        $this->cicloEscolarRepository = new CicloEscolarRepository;
        $this->usuarioServicioRepository = new UsuarioServicioRepository;
        $this->usuarioProgramaRepository = new UsuarioProgramaRepository;

        $this->carreras = $this->carreraRepository->pluckByIdAbreviacion();
        $this->servicios = $this->servicioRepository->pluckByIdNombre();
        $this->programas = $this->programaRepository->pluckByIdNombre();
        $this->ciclo_escolar = $this->cicloEscolarRepository->findLast();
    }

    public function collection(Collection $rows)
    {
        foreach ($rows as $row)
        {
            $usuario_actual = $this->usuarioRepository->findByCorreo($row['correo_uanl']);
            $id_usuario = null;

            if ($usuario_actual == null){
                
                $usuario_subido = $this->usuarioRepository->getUsuarioModel();

                $usuario_subido->nombre = $row['nombre_asesor'];
                $usuario_subido->apellido_pat = $row['apellido_paterno'];
                $usuario_subido->apellido_mat = $row['apellido_materno'];
                $usuario_subido->matricula = $row['matricula'];
                $usuario_subido->correo_universitario = $row['correo_uanl'];
                $usuario_subido->id_carrera = $this->carreras[$row['carrera']];
                $usuario_subido->id_ciclo_escolar = $this->ciclo_escolar->id;
                $usuario_subido->id_rotacion = $row['id_rotacion'];

                //TODO: ESTO RETORNA EL ID?
                $usuario_subido = $this->usuarioRepository->save($usuario_subido);

                $id_usuario = $usuario_subido->id;
            }
            else{
                $id_usuario = $usuario_actual->id;
            }

            $usuario_servicio = $this->usuarioServicioRepository->getByUsuario($id_usuario);

            if ($usuario_servicio == null){
                $this->usuarioServicioRepository->create($id_usuario, $this->servicios[$row['servicio']]);
            }
            else if ($usuario_servicio->id_servicio !== $this->servicios[$row['servicio']]){
                $this->usuarioServicioRepository->create($id_usuario, $this->servicios[$row['servicio']]);
            }

            if ($row['servicio'] == "Servicio Social"){
                $usuario_programa =  $this->usuarioProgramaRepository->getByUsuario($id_usuario);

                if ($usuario_programa == null){
                    $this->usuarioProgramaRepository->create($id_usuario, $this->programas[$row['programa']]);
                }
                else if ($usuario_programa->id_programa !== $this->programas[$row['programa']]){
                    $this->usuarioProgramaRepository->create($id_usuario, $this->programas[$row['programa']]);
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
