<?php

namespace Database\Seeders;

use App\Models\Usuario;
use Faker\Factory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UsuarioPruebaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Factory(Horari)


        Usuario::create([
            'nombre' => 'Felix Leopoldo',
            'apellido_pat' => 'Lara',
            'apellido_mat' => 'Sanchez',
            'matricula' => '1843737',
            'correo_universitario' => 'felix.larasz@uanl.edu.mx',
            'id_rotacion' => 2,
            'id_horario' => 1,
            'id_carrera' => 1,
            'id_ciclo_escolar' => 1
        ]);
    }
}
