<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UsuarioServicio extends Model
{
    use HasFactory;
    protected $table = 'usuarios_servicios';
    protected $fillable = [
        'id_usuario',
        'id_servicio'
    ];

    public function id_usuario(){
        return $this->hasOne(Usuario::class, 'id', 'id_usuario');
    }

    public function id_servicio(){
        return $this->hasOne(Servicio::class, 'id', 'id_servicio');
    }
}
