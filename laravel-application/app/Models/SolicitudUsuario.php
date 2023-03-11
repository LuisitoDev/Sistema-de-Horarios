<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SolicitudUsuario extends Model
{
    use HasFactory;

    protected $table = 'solicitudes_usuarios';
    protected $fillable = [
        'id',
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

    //TODO: RELACION - PENDIENTE PROBAR
    public function carrera() : BelongsTo {
        return $this->belongsTo(Carrera::class, 'id_carrera', 'id' );
    }

    //TODO: RELACION - PENDIENTE PROBAR
    public function servicio() : BelongsTo {
        return $this->belongsTo(Servicio::class, 'id_servicio_usuario', 'id' );
    }

    //TODO: RELACION - PENDIENTE PROBAR
    public function programa() : BelongsTo {
        return $this->belongsTo(Programa::class, 'id_programa_usuario', 'id');
    }



}
