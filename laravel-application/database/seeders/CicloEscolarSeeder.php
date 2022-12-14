<?php

namespace Database\Seeders;

use App\Models\CicloEscolar;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CicloEscolarSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        CicloEscolar::create([
            'fecha_ingreso' => '2022-01-03',
            'fecha_salida' => '2022-05-31'
        ]);

        CicloEscolar::create([
            'fecha_ingreso' => '2022-06-01',
            'fecha_salida' => '2022-11-30'
        ]);

    }
}
