<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([DiasSeeder::class]);
        $this->call([AdministradorSeeder::class]);
        $this->call([CicloEscolarSeeder::class]);
        $this->call([CarreraSeeder::class]);
        $this->call([ServicioSeeder::class]);
        $this->call([ProgramaSeeder::class]);
        $this->call([StatusSeeder::class]);
        $this->call([RotacionSeeder::class]);
        // $this->call([UsuarioSeeder::class]);
        // $this->call([HorarioSeeder::class]);
        // $this->call([TurnoDiarioSeeder::class]);
        // $this->call([EntradaSeeder::class]);
        // $this->call([UsuarioServicioSeeder::class]);

    }
}
