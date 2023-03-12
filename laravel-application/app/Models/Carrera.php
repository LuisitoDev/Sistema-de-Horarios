<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Carrera extends Model
{
    use HasFactory;

    public const table_name = "carreras";
    public const id = "id";
    public const nombre = "nombre";
    public const abreviacion = "abreviacion";
    
    protected $table = self::table_name;
    protected $fillable = [
        self::id,
        self::nombre,
        self::abreviacion
    ];

    public function usuarios() : HasMany
    {
        return $this->hasMany(Usuario::class, Usuario::id_carrera, self::id);
    }

    public function solicitudesUsuario() : HasMany
    {
        return $this->hasMany(SolicitudUsuario::class, SolicitudUsuario::id_carrera_usuario, self::id);
    }

    public function format()
    {
        return $this;
    }
}
