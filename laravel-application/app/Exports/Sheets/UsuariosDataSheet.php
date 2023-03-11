<?php

namespace App\Exports\Sheets;

use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;

class UsuariosDataSheet implements FromCollection, WithHeadings, WithTitle
{
    use Exportable;

    private $dayFrom;
    private $dayTo;

    public function __construct($dayFrom, $dayTo)
    {
        $this->dayFrom = $dayFrom;
        $this->dayTo = $dayTo;
    }

    public function headings(): array {
        return [
            'Matricula',
            'Nombre_Asesor',
            'Apellido_Paterno',
            'Apellido_Materno',
            'Correo_UANL',
            'Carrera',
            'Servicio',
            'Programa',
            'Id_rotacion',
            '',
            '',
            'Reporte desde dia "'.$this->dayFrom. '" hasta "'.$this->dayTo.'"'
        ];
    }

    public function collection()
    {
        $dayFrom = $this->dayFrom;
        $dayTo = $this->dayTo;

        return DB::table('usuarios')
            ->select(
                'usuarios.matricula as matricula', // SI
                'usuarios.nombre as nombre_alumno', // SI
                'usuarios.apellido_pat as apellido_paterno', // SI
                'usuarios.apellido_mat as apellido_materno', // SI
                'usuarios.correo_universitario as correo_universitario', // SI
                'carreras.abreviacion as carrera', // SI
                'servicios.nombre as servicios', // SI
                'programas.nombre as programas', // SI
                'usuarios.id_rotacion as id_rotacion', // SI
            )
            ->join('carreras', 'usuarios.id_carrera', '=', 'carreras.id')
            ->join('ciclo_escolar', 'ciclo_escolar.id', '=', 'usuarios.id_ciclo_escolar')
            ->leftjoin('usuarios_servicios', 'usuarios_servicios.id_usuario', '=', 'usuarios.id')
            ->leftjoin('servicios', 'usuarios_servicios.id_servicio', '=', 'servicios.id')
            ->leftJoin('usuarios_programas', 'usuarios.id', '=', 'usuarios_programas.id_usuario')
            ->leftJoin('programas', 'usuarios_programas.id_programa', '=', 'programas.id')
            ->when($dayFrom != null && $dayTo != null, function($query) use ($dayFrom, $dayTo){
                return $query->where(function ($query) use ($dayFrom, $dayTo) {
                    $query->whereRaw(
                        "(ciclo_escolar.fecha_ingreso <= ? && ciclo_escolar.fecha_salida >= ?)
                        OR  (ciclo_escolar.fecha_ingreso <= ? && ciclo_escolar.fecha_salida >= ?)
                        OR  (ciclo_escolar.fecha_ingreso >= ? && ciclo_escolar.fecha_salida <= ?)",
                        [$dayFrom, $dayFrom, $dayTo, $dayTo, $dayFrom, $dayTo]);
                });
            })
            ->get();
    }

    public function title(): string
    {
        return 'Datos';
    }
}
