<?php

namespace App\Exports\Sheets;

use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use Illuminate\Support\Collection;

class UsuariosHorariosSheet implements FromCollection, WithHeadings, WithTitle
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
            'Correo_UANL',
            'Lunes_Entrada',
            'Lunes_Salida',
            'Martes_Entrada',
            'Martes_Salida',
            'Miercoles_Entrada',
            'Miercoles_Salida',
            'Jueves_Entrada',
            'Jueves_Salida',
            'Viernes_Entrada',
            'Viernes_Salida',
        ];
    }

    public function collection()
    {

        $dayFrom = $this->dayFrom;
        $dayTo = $this->dayTo;

        $horariosExport = collect([]);
        $horariosAlumno = [];

        $iteracionLunes = 0;
        $iteracionMartes = 0;
        $iteracionMiercoles = 0;
        $iteracionJueves = 0;
        $iteracionViernes = 0;

        $query = DB::table('usuarios')
            ->select(
                'usuarios.correo_universitario as correo_universitario',
                'turnos_diarios.hora_entrada as hora_entrada',
                'turnos_diarios.hora_salida as hora_salida',
                'dias.nombre as dia'
            )
            ->join('ciclo_escolar', 'ciclo_escolar.id', '=', 'usuarios.id_ciclo_escolar')
            ->join('horarios', 'usuarios.id', '=', 'horarios.id_usuario')
            ->join('turnos_diarios', 'horarios.id', '=', 'turnos_diarios.id_horario')
            ->join('dias', 'turnos_diarios.dia', '=', 'dias.id')
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

        $alumnoActual = $query->first()->correo_universitario;

        foreach($query as $row) {

            if ($alumnoActual != $row->correo_universitario) {
                foreach ($horariosAlumno as $v1) {
                    if (!isset($v1['lunes_entrada']))       { $v1['lunes_entrada'] = ''; }
                    if (!isset($v1['lunes_salida']))        { $v1['lunes_salida'] = ''; }
                    if (!isset($v1['martes_entrada']))      { $v1['martes_entrada'] = ''; }
                    if (!isset($v1['martes_salida']))       { $v1['martes_salida'] = ''; }
                    if (!isset($v1['miercoles_entrada']))   { $v1['miercoles_entrada'] = ''; }
                    if (!isset($v1['miercoles_salida']))    { $v1['miercoles_salida'] = ''; }
                    if (!isset($v1['jueves_entrada']))      { $v1['jueves_entrada'] = ''; }
                    if (!isset($v1['jueves_salida']))       { $v1['jueves_salida'] = ''; }
                    if (!isset($v1['viernes_entrada']))     { $v1['viernes_entrada'] = ''; }
                    if (!isset($v1['viernes_salida']))      { $v1['viernes_salida'] = ''; }

                    $horariosExport->add([
                        'correo_universitario'  =>$alumnoActual,
                        'lunes_entrada'         =>$v1['lunes_entrada'],
                        'lunes_salida'          =>$v1['lunes_salida'],
                        'martes_entrada'        =>$v1['martes_entrada'],
                        'martes_salida'         =>$v1['martes_salida'],
                        'miercoles_entrada'     =>$v1['miercoles_entrada'],
                        'miercoles_salida'      =>$v1['miercoles_salida'],
                        'jueves_entrada'        =>$v1['jueves_entrada'],
                        'jueves_salida'         =>$v1['jueves_salida'],
                        'viernes_entrada'       =>$v1['viernes_entrada'],
                        'viernes_salida'        =>$v1['viernes_salida']
                    ]);
                }

                unset($horariosAlumno);
                $iteracionLunes = 0;
                $iteracionMartes = 0;
                $iteracionMiercoles = 0;
                $iteracionJueves = 0;
                $iteracionViernes = 0;
                $alumnoActual = $row->correo_universitario;
            }

            if ($row->dia == 'Lunes') {
                $horariosAlumno[$iteracionLunes]['lunes_entrada'] = $row->hora_entrada;
                $horariosAlumno[$iteracionLunes]['lunes_salida'] = $row->hora_salida;
                $iteracionLunes ++;
            }

            if ($row->dia == 'Martes') {
                $horariosAlumno[$iteracionMartes]['martes_entrada'] = $row->hora_entrada;
                $horariosAlumno[$iteracionMartes]['martes_salida'] = $row->hora_salida;
                $iteracionMartes ++;
            }

            if ($row->dia == 'Miercoles') {
                $horariosAlumno[$iteracionMiercoles]['miercoles_entrada'] = $row->hora_entrada;
                $horariosAlumno[$iteracionMiercoles]['miercoles_salida'] = $row->hora_salida;
                $iteracionMiercoles ++;
            }

            if ($row->dia == 'Jueves') {
                $horariosAlumno[$iteracionJueves]['jueves_entrada'] = $row->hora_entrada;
                $horariosAlumno[$iteracionJueves]['jueves_salida'] = $row->hora_salida;
                $iteracionJueves ++;
            }

            if ($row->dia == 'Viernes') {
                $horariosAlumno[$iteracionViernes]['viernes_entrada'] = $row->hora_entrada;
                $horariosAlumno[$iteracionViernes]['viernes_salida'] = $row->hora_salida;
                $iteracionViernes ++;
            }

            // PARA PODER GUARDAR EL ULTIMO ELEMENTO QUE NOMAS NO SE COMO GUARDARLO AYUDA
            if ($row == $query->last()) {
                foreach ($horariosAlumno as $v1) {
                    if (!isset($v1['lunes_entrada']))       { $v1['lunes_entrada'] = ''; }
                    if (!isset($v1['lunes_salida']))        { $v1['lunes_salida'] = ''; }
                    if (!isset($v1['martes_entrada']))      { $v1['martes_entrada'] = ''; }
                    if (!isset($v1['martes_salida']))       { $v1['martes_salida'] = ''; }
                    if (!isset($v1['miercoles_entrada']))   { $v1['miercoles_entrada'] = ''; }
                    if (!isset($v1['miercoles_salida']))    { $v1['miercoles_salida'] = ''; }
                    if (!isset($v1['jueves_entrada']))      { $v1['jueves_entrada'] = ''; }
                    if (!isset($v1['jueves_salida']))       { $v1['jueves_salida'] = ''; }
                    if (!isset($v1['viernes_entrada']))     { $v1['viernes_entrada'] = ''; }
                    if (!isset($v1['viernes_salida']))      { $v1['viernes_salida'] = ''; }

                    $horariosExport->add([
                        'correo_universitario'  =>$alumnoActual,
                        'lunes_entrada'         =>$v1['lunes_entrada'],
                        'lunes_salida'          =>$v1['lunes_salida'],
                        'martes_entrada'        =>$v1['martes_entrada'],
                        'martes_salida'         =>$v1['martes_salida'],
                        'miercoles_entrada'     =>$v1['miercoles_entrada'],
                        'miercoles_salida'      =>$v1['miercoles_salida'],
                        'jueves_entrada'        =>$v1['jueves_entrada'],
                        'jueves_salida'         =>$v1['jueves_salida'],
                        'viernes_entrada'       =>$v1['viernes_entrada'],
                        'viernes_salida'        =>$v1['viernes_salida']
                    ]);
                }
            }
        }

        return $horariosExport;
    }

    public function title(): string
    {
        return 'Horarios';
    }
}
