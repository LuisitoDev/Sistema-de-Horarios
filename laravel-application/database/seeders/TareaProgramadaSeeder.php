<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\TareaProgramada;

class TareaProgramadaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        TareaProgramada::create([
            'id' => 1,
            'nombre_tarea' => 'Cierre de horas'
        ]);

        TareaProgramada::create([
            'id' => 2,
            'nombre_tarea' => 'Cambio de Rotacion'
        ]);
    }
}
