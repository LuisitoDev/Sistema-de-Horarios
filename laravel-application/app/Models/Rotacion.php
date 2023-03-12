<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Rotacion extends Model
{
    use HasFactory;

    public const table_name = "rotaciones";
    public const id = "id";
    public const lunes_presencial = "lunes_presencial";
    public const martes_presencial = "martes_presencial";
    public const miercoles_presencial = "miercoles_presencial";
    public const jueves_presencial = "jueves_presencial";
    public const viernes_presencial = "viernes_presencial";

    protected $table = self::table_name;
    protected $fillable = [
        self::id,
        self::lunes_presencial,
        self::martes_presencial,
        self::miercoles_presencial,
        self::jueves_presencial,
        self::viernes_presencial
    ];

    public function usuarios() : HasMany
    {
        return $this->hasMany(Usuario::class, Usuario::id_rotacion, self::id);
    }

    public function format()
    {
        return $this;
    }
}
