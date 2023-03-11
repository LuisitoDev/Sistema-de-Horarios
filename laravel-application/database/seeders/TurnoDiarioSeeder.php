<?php

namespace Database\Seeders;

use App\Models\TurnoDiario;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TurnoDiarioSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        TurnoDiario::create([
            'hora_entrada' => '11:00:00',
            'hora_salida' => '15:00:00',
            'dia' => 1,
            'id_horario' => '1'
        ]);

        TurnoDiario::create([
            'hora_entrada' => '11:00:00',
            'hora_salida' => '15:00:00',
            'dia' => 2,
            'id_horario' => '1'
        ]);

        TurnoDiario::create([
            'hora_entrada' => '11:00:00',
            'hora_salida' => '15:00:00',
            'dia' => 3,
            'id_horario' => '1'
        ]);

        TurnoDiario::create([
            'hora_entrada' => '11:00:00',
            'hora_salida' => '15:00:00',
            'dia' => 4,
            'id_horario' => '1'
        ]);

        TurnoDiario::create([
            'hora_entrada' => '11:00:00',
            'hora_salida' => '15:00:00',
            'dia' => 5,
            'id_horario' => '1'
        ]);
    }
}