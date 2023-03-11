<?php

namespace App\Exports\Sheets;

use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;

class EntradasInformeGeneralSheet implements FromCollection, WithHeadings, WithTitle
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
            'Nombre',
            'Correo',
            'Programa',
            'Carrera',
            'Entradas',
            'Horas por reponer',
            'Cumplio la entrada',
            'Cumplio online',
            'Cumplio, pero cerro tarde',
            'Cumplio la entrada, pero incompleta',
            'No cumplio la entrada',
            'No marco salida',
            'Trabajando',
        ];
    }

    public function collection()
    {
        $dayFrom = $this->dayFrom;
        $dayTo = $this->dayTo;

        return DB::table('usuarios')
            ->select(
                'usuarios.matricula as matricula',
                DB::raw("CONCAT(usuarios.nombre, ' ', usuarios.apellido_pat, ' ', usuarios.apellido_mat) as nombre"),
                'usuarios.correo_universitario as correo_universitario',
                'servicios.nombre as servicio_nombre',
                'carreras.abreviacion as carrera',
                DB::raw("if(count(entradas.id_usuario) = 0, '0', count(entradas.id_usuario)) as entradas"),
                DB::raw("ifnull(sum(entradas.horas_realizadas_programada) - sum(entradas.horas_realizadas), '0') as horas_pendientes"),
                DB::raw("ifnull(SUM(case when entradas.id_status = '1' then 1 end), '0') as cumplio_entrada"),
                DB::raw("ifnull(SUM(case when entradas.id_status = '7' then 1 end), '0') as cumplio_online"),
                DB::raw("ifnull(SUM(case when entradas.id_status = '5' then 1 end), '0') as cumplio_entrada_cerro_tarde"),
                DB::raw("ifnull(SUM(case when entradas.id_status = '3' then 1 end), '0') as cumplio_entrada_incompleta"),
                DB::raw("ifnull(SUM(case when entradas.id_status = '2' then 1 end), '0') as no_cumplio"),
                DB::raw("ifnull(SUM(case when entradas.id_status = '6' then 1 end), '0') as no_marco_salida"),
                DB::raw("ifnull(SUM(case when entradas.id_status = '4' then 1 end), '0') as trabajando"),
            )
            ->leftjoin('carreras', 'usuarios.id_carrera','=', 'carreras.id')
            ->join('ciclo_escolar', 'ciclo_escolar.id', '=', 'usuarios.id_ciclo_escolar')
            ->leftjoin('usuarios_servicios', 'usuarios_servicios.id_usuario', '=', 'usuarios.id')
            ->leftjoin('servicios', 'usuarios_servicios.id_servicio', '=', 'servicios.id')
            ->leftJoin('entradas', function ($leftJoin) use ($dayFrom, $dayTo) {
                $leftJoin->on('usuarios.id', '=', 'entradas.id_usuario')
                ->whereDate('entradas.hora_entrada_programada', '>=', $dayFrom)
                ->whereDate('entradas.hora_entrada_programada', '<=', $dayTo);
            })
            ->groupBy(
                'usuarios.id',
                'usuarios.matricula',
                'usuarios.nombre',
                'usuarios.apellido_pat',
                'usuarios.apellido_mat',
                'usuarios.correo_universitario',
                'usuarios.id_carrera',
                'carreras.abreviacion',
                'servicio_nombre'
            )
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
        return 'Informe general';
    }
}
