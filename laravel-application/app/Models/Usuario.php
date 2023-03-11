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

    protected $table = 'usuarios';
    protected $fillable = [
        'id',
        'nombre',
        'apellido_pat',
        'apellido_mat',
        'matricula',
        'correo_universitario',
        'fecha_creacion',
        'estado',
        'imagen',
        'id_carrera',
        'id_ciclo_escolar',
        'id_rotacion'
    ];



    public function carrera() : BelongsTo {
        return $this->belongsTo(Carrera::class, 'id_carrera', 'id');
    }

    public function ciclo_escolar() : BelongsTo {
        return $this->belongsTo(CicloEscolar::class, 'id_ciclo_escolar', 'id');
    }


    public function rotacion() : BelongsTo {
        return $this->belongsTo(Rotacion::class, 'id_rotacion', 'id');
    }

    
    public function dispositivos() : HasMany
    {
        return $this->hasMany(Dispositivo::class, "id_usuario", "id");
    }

    public function entradas() : HasMany
    {
        return $this->hasMany(Entrada::class, "id_usuario", "id");
    }

    public function horario() : HasOne
    {
        return $this->hasOne(Horario::class, "id_usuario", "id");
    }

    public function solicitudesDispositivos() : HasMany
    {
        return $this->hasMany(SolicitudDispositivo::class, "id_usuario", "id");
    }

    public function programas() : BelongsToMany
    {
        return $this->belongsToMany(Programa::class, UsuarioPrograma::class, "id_usuario", "id_programa");
    }

    public function servicios() : BelongsToMany
    {
        return $this->belongsToMany(Servicio::class, UsuarioServicio::class, "id_usuario", "id_servicio");
    }

    public function format()
    {
        return $this;
    }
}
