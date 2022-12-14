<?php

namespace Database\Seeders;

use App\Models\Rotacion;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RotacionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Rotacion::create([
            'id' => 1,
            'lunes_presencial' => true,
            'martes_presencial' => true,
            'miercoles_presencial' => true,
            'jueves_presencial' => true,
            'viernes_presencial' => true
        ]);

        Rotacion::create([
            'id' => 2,
            'lunes_presencial' => true,
            'miercoles_presencial' => true,
            'viernes_presencial' => true
        ]);

        Rotacion::create([
            'id' => 3,
            'martes_presencial' => true,
            'jueves_presencial' => true
        ]);
    }
}
