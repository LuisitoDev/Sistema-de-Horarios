<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SolicitudUsuario extends Model
{
    use HasFactory;

    public const table_name = "solicitudes_usuarios";
    public const id = "id";
    public const nombre_usuario = "nombre_usuario";
    public const apellido_pat_usuario = "apellido_pat_usuario";
    public const apellido_mat_usuario = "apellido_mat_usuario";
    public const matricula_usuario = "matricula_usuario";
    public const correo_universitario_usuario = "correo_universitario_usuario";
    public const direccion_mac_dispositivo = "direccion_mac_dispositivo";
    public const imagen_usuario = "imagen_usuario";
    public const id_carrera_usuario = "id_carrera_usuario";
    public const id_servicio_usuario = "id_servicio_usuario";
    public const id_programa_usuario = "id_programa_usuario";

    public const created_at = "created_at";
    public const updated_at = "updated_at";
    public const deleted_at = "deleted_at";

    protected $table = self::table_name;
    protected $fillable = [
        self::id,
        self::nombre_usuario,
        self::apellido_pat_usuario,
        self::apellido_mat_usuario,
        self::matricula_usuario,
        self::correo_universitario_usuario,
        self::direccion_mac_dispositivo,
        self::imagen_usuario,
        self::id_carrera_usuario,
        self::id_servicio_usuario,
        self::id_programa_usuario
    ];

    //TODO: RELACION - PENDIENTE PROBAR
    public function carrera() : BelongsTo {
        return $this->belongsTo(Carrera::class, self::id_carrera_usuario, Carrera::id );
    }

    //TODO: RELACION - PENDIENTE PROBAR
    public function servicio() : BelongsTo {
        return $this->belongsTo(Servicio::class, self::id_servicio_usuario, Servicio::id );
    }

    //TODO: RELACION - PENDIENTE PROBAR
    public function programa() : BelongsTo {
        return $this->belongsTo(Programa::class, self::id_programa_usuario, Programa::id);
    }

    public function format()
    {
        return $this;
    }

}
