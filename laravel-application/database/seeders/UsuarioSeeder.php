<?php

namespace Database\Seeders;

use App\Models\Usuario;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UsuarioSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Usuario::create([
            'nombre' => 'Juan',
            'apellido_pat' => 'Estrada',
            'apellido_mat' => 'Escutia',
            'matricula' => '123',
            'correo_universitario' => 'juan@uanl.edu.mx',
            'estado' => 1,
            'fecha_creacion' => '2021-04-16',
            'id_carrera' => 1,
            'id_ciclo_escolar' => 1,
            'id_rotacion' => 1
        ]);
    }
}
