<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SolicitudDispositivo extends Model
{
    use HasFactory;
    
    protected $table = 'solicitudes_dispositivos';
    protected $fillable = [
        'direccion_mac_dispositivo',
        'id_usuario'
    ];

    public function id_usuario(){
        return $this->hasOne(Usuario::class, 'id', 'id_usuario');
    }
}
