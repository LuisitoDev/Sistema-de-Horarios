<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Usuario extends Model
{
    use HasFactory;
    use SoftDeletes;


    public const table_name = "usuarios";
    public const id = "id";
    public const nombre = "nombre";
    public const apellido_pat = "apellido_pat";
    public const apellido_mat = "apellido_mat";
    public const matricula = "matricula";
    public const correo_universitario = "correo_universitario";
    public const fecha_creacion = "fecha_creacion";
    public const estado = "estado";
    public const imagen = "imagen";
    public const id_carrera = "id_carrera";
    public const id_ciclo_escolar = "id_ciclo_escolar";
    public const id_servicio_usuario = "id_servicio_usuario";
    public const id_rotacion = "id_rotacion";

    public const created_at = "created_at";
    public const updated_at = "updated_at";
    public const deleted_at = "deleted_at";
    
    protected $table = self::table_name;
    protected $fillable = [
        self::id,
        self::nombre,
        self::apellido_pat,
        self::apellido_mat,
        self::matricula,
        self::correo_universitario,
        self::fecha_creacion,
        self::estado,
        self::imagen,
        self::id_carrera,
        self::id_ciclo_escolar,
        self::id_rotacion
    ];



    public function carrera() : BelongsTo {
        return $this->belongsTo(Carrera::class, self::id_carrera, Carrera::id);
    }

    public function ciclo_escolar() : BelongsTo {
        return $this->belongsTo(CicloEscolar::class, self::id_ciclo_escolar, CicloEscolar::id);
    }


    public function rotacion() : BelongsTo {
        return $this->belongsTo(Rotacion::class, self::id_rotacion, Rotacion::id);
    }

    
    public function dispositivos() : HasMany
    {
        return $this->hasMany(Dispositivo::class, Dispositivo::id_usuario, self::id);
    }

    public function entradas() : HasMany
    {
        return $this->hasMany(Entrada::class, Entrada::id_usuario, self::id);
    }

    public function horario() : HasOne
    {
        return $this->hasOne(Horario::class, Horario::id_usuario, self::id);
    }

    public function solicitudesDispositivos() : HasMany
    {
        return $this->hasMany(SolicitudDispositivo::class, SolicitudDispositivo::id_usuario, self::id);
    }

    public function programas() : BelongsToMany
    {
        return $this->belongsToMany(Programa::class, UsuarioPrograma::class, UsuarioPrograma::id_usuario, UsuarioPrograma::id_programa);
    }

    public function servicios() : BelongsToMany
    {
        return $this->belongsToMany(Servicio::class, UsuarioServicio::class, UsuarioServicio::id_usuario, UsuarioServicio::id_servicio);
    }

    public function format()
    {
        $this->imagen = base64_encode($this->imagen);

        return $this;
    }
}
