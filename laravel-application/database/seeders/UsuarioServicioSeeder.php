<?php

namespace Database\Seeders;

use App\Models\UsuarioServicio;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UsuarioServicioSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        UsuarioServicio::create([
            'id_usuario' => '1',
            'id_servicio' => '1'
        ]);
    }
}
