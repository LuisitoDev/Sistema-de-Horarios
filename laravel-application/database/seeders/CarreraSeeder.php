<?php

namespace Database\Seeders;

use App\Models\Carrera;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CarreraSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Carrera::create([
            'nombre' => 'Licenciatura en Multimedia y Animacion Digital',
            'abreviacion' => 'LMAD'
        ]);

        Carrera::create([
            'nombre' => 'Licenciatura en Actuaria',
            'abreviacion' => 'LA'
        ]);

        Carrera::create([
            'nombre' => 'Licenciatura en Matemáticas',
            'abreviacion' => 'LM'
        ]);

        Carrera::create([
            'nombre' => 'Licenciatura en Fisica',
            'abreviacion' => 'LF'
        ]);

        Carrera::create([
            'nombre' => 'Licenciatura en Ciencias Computacionales',
            'abreviacion' => 'LCC'
        ]);

        Carrera::create([
            'nombre' => 'Licenciatura en Seguridad en Tecnologías de Información',
            'abreviacion' => 'LSTI'
        ]);
    }
}
