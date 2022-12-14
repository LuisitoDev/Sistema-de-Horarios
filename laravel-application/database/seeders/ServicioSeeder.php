<?php

namespace Database\Seeders;

use App\Models\Servicio;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ServicioSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Servicio::create([
            'nombre' => 'Talentos',
            'horas_totales' => 80,
        ]);

        Servicio::create([
            'nombre' => 'Servicio Social',
            'horas_totales' => 480,
        ]);

        Servicio::create([
            'nombre' => 'Becarios',
            'horas_totales' => 480,
        ]);
    }
}
