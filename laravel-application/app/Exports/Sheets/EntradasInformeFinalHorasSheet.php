<?php

namespace App\Exports\Sheets;

use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;

class EntradasInformeFinalHorasSheet implements FromCollection, WithHeadings, WithTitle
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
            'Correo',
            'Nombre del empleado',
            'Hora_Entrada',
            'Hora_Salida',
            'Hora_Inicio_Turno',
            'Hora_Finalizacion_Turno',
            'Horas_Programadas',
            'Horas_Trabajadas',
            'Estado',
            '',
            '',
            'Reporte desde dia "'.$this->dayFrom. '" hasta "'.$this->dayTo.'"'
        ];
    }

    public function collection()
    {
        $dayFrom = $this->dayFrom;
        $dayTo = $this->dayTo;

        return DB::table('entradas')
            ->select(
                'usuarios.correo_universitario as correo_universitario',
                DB::raw("CONCAT(usuarios.nombre, ' ', usuarios.apellido_pat, ' ', usuarios.apellido_mat) as nombre_usuario"),
                'entradas.hora_entrada as hora_entrada',
                'entradas.hora_salida as hora_salida',
                'entradas.hora_entrada_programada as hora_entrada_programada',
                'entradas.hora_salida_programada as hora_salida_programada',
                'entradas.horas_realizadas_programada as horas_realizadas_programada',
                'entradas.horas_realizadas as horas_realizadas',
                'status.nombre as status'
            )
            ->join('usuarios', 'usuarios.id', '=', 'entradas.id_usuario')
            ->join('status', 'status.id', '=', 'entradas.id_status')
            ->whereDate('entradas.hora_entrada_programada', '>=', $dayFrom)
            ->whereDate('entradas.hora_entrada_programada', '<=', $dayTo)
            ->get();
    }

    public function title(): string
    {
        return 'Informe final de reloj de horas';
    }
}
