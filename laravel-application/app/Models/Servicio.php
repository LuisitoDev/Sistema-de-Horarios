<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Servicio extends Model
{
    use HasFactory;

    public const table_name = "servicios";
    public const id = "id";
    public const nombre = "nombre";
    public const horas_totales = "horas_totales";
    public const horas_por_dia = "horas_por_dia";

    protected $table = self::table_name;
    protected $fillable = [
        self::id,
        self::nombre,
        self::horas_totales,
        self::horas_por_dia
    ];

    public function usuarios() : BelongsToMany
    {
        return $this->belongsToMany(Usuario::class, UsuarioServicio::class, UsuarioServicio::id_servicio, UsuarioServicio::id_usuario);
    }

    public function solicitudUsuario() : HasOne
    {
        return $this->hasOne(SolicitudUsuario::class, self::id, SolicitudUsuario::id_servicio_usuario);
    }

    public function format()
    {
        return $this;
    }
}
