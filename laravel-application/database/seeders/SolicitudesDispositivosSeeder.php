<?php

namespace Database\Seeders;

use App\Models\SolicitudDispositivo;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SolicitudesDispositivosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        SolicitudDispositivo::create([
            'id_usuario' => 2,
            'direccion_mac_dispositivo' => 'ef-3a-dd-ff-a4-c2'
        ]);

        SolicitudDispositivo::create([
            'id_usuario' => 2,
            'direccion_mac_dispositivo' => 'bb-3a-dd-cc-a4-c2'
        ]);

        SolicitudDispositivo::create([
            'id_usuario' => 1,
            'direccion_mac_dispositivo' => 'df-3a-dd-5x-a4-c2'
        ]);
    }
}
