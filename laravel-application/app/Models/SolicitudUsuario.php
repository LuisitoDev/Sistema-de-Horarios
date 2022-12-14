<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SolicitudUsuario extends Model
{
    use HasFactory;

    protected $table = 'solicitudes_usuarios';
    protected $fillable = [
        'nombre_usuario',
        'apellido_pat_usuario',
        'apellido_mat_usuario',
        'matricula_usuario',
        'correo_universitario_usuario',
        'direccion_mac_dispositivo',
        'imagen_usuario',
        'id_carrera_usuario',
        'id_servicio_usuario',
        'id_programa_usuario'
    ];

    public function id_carrera_usuario(){
        return $this->hasOne(Carrera::class, 'id', 'id_carrera_usuario');
    }

    public function id_servicio_usuario(){
        return $this->hasOne(Servicio::class, 'id', 'id_servicio_usuario');
    }

    public function id_programa_usuario(){
        return $this->hasOne(Programa::class, 'id', 'id_programa_usuario');
    }
}
