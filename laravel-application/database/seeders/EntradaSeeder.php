<?php

namespace Database\Seeders;

use App\Models\Entrada;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EntradaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Entrada::create([
            'hora_entrada_programada' => '2022-01-03 12:00:00',
            'hora_salida_programada' => '2022-01-03 16:00:00',
            'horas_realizadas_programada' => '4',
            'hora_entrada' => '2022-01-03 12:00:00',
            'hora_salida' => '2022-01-03 16:00:00',
            'horas_realizadas' => '4',
            'reporte_diario' => 'Reporte lunes',
            'id_status' => '1',
            'id_usuario' => '1'
        ]);

        Entrada::create([
            'hora_entrada_programada' => '2022-01-04 12:00:00',
            'hora_salida_programada' => '2022-01-04 16:00:00',
            'horas_realizadas_programada' => '4',
            'hora_entrada' => '2022-01-04 12:00:00',
            'hora_salida' => '2022-01-04 16:00:00',
            'horas_realizadas' => '4',
            'reporte_diario' => 'Reporte martes',
            'id_status' => '1',
            'id_usuario' => '1'
        ]);

        Entrada::create([
            'hora_entrada_programada' => '2022-01-05 12:00:00',
            'hora_salida_programada' => '2022-01-05 16:00:00',
            'horas_realizadas_programada' => '4',
            'hora_entrada' => '2022-01-05 12:00:00',
            'hora_salida' => '2022-01-05 16:00:00',
            'horas_realizadas' => '4',
            'reporte_diario' => 'Reporte miercoles',
            'id_status' => '1',
            'id_usuario' => '1'
        ]);

        Entrada::create([
            'hora_entrada_programada' => '2022-01-06 12:00:00',
            'hora_salida_programada' => '2022-01-06 16:00:00',
            'horas_realizadas_programada' => '4',
            'hora_entrada' => '2022-01-06 12:00:00',
            'hora_salida' => '2022-01-06 16:00:00',
            'horas_realizadas' => '4',
            'reporte_diario' => 'Reporte jueves',
            'id_status' => '1',
            'id_usuario' => '1'
        ]);

        Entrada::create([
            'hora_entrada_programada' => '2022-01-07 12:00:00',
            'hora_salida_programada' => '2022-01-07 16:00:00',
            'horas_realizadas_programada' => '4',
            'hora_entrada' => '2022-01-07 12:00:00',
            'hora_salida' => '2022-01-07 16:00:00',
            'horas_realizadas' => '4',
            'reporte_diario' => 'Reporte viernes',
            'id_status' => '1',
            'id_usuario' => '1'
        ]);
    }
}
