<?php

namespace Database\Seeders;

use App\Models\Programa;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProgramaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Programa::create([
            'nombre' => 'Recepcionista'
        ]);

        Programa::create([
            'nombre' => 'Asesorias'
        ]);

        Programa::create([
            'nombre' => 'Tutorias'
        ]);

        Programa::create([
            'nombre' => 'Video'
        ]);
    }
}
