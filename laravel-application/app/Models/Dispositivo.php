<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dispositivo extends Model
{
    use HasFactory;

    protected $table = 'dispositivos';
    protected $fillable = [
        'direccion_mac',
        'id_usuario'
    ];

    public function id_usuario(){
        return $this->hasOne(Usuario::class, 'id', 'id_usuario');
    }
}
