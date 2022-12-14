<?php

namespace Database\Seeders;

use App\Models\Status;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class StatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Status::create([
            'id' => '1',
            'nombre' => 'Cumplio la entrada'
        ]);

        Status::create([
            'id' => '2',
            'nombre' => 'No cumplio la entrada'
        ]);

        Status::create([
            'id' => '3',
            'nombre' => 'Cumplio la entrada, pero incompleta'
        ]);

        Status::create([
            'id' => '4',
            'nombre' => 'Trabajando'
        ]);

        Status::create([
            'id' => '5',
            'nombre' => 'Cumplio, pero cerro tarde'
        ]);

        Status::create([
            'id' => '6',
            'nombre' => 'No marco salida'
        ]);

        Status::create([
            'id' => '7',
            'nombre' => 'Cumplio online'
        ]);
    }
}
