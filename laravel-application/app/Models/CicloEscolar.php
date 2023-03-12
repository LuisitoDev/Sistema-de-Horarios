<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CicloEscolar extends Model
{
    use HasFactory;

    public const table_name = "ciclo_escolar";
    public const id = "id";
    public const fecha_ingreso = "fecha_ingreso";
    public const fecha_salida = "fecha_salida";

    protected $table = self::table_name;
    protected $fillable = [
        self::id,
        self::fecha_ingreso,
        self::fecha_salida
    ];

    //TODO: PENDIENTE TESTEAR
    public function usuarios() : HasMany
    {
        return $this->hasMany(Usuario::class, Usuario::id_ciclo_escolar, self::id);
    }

    public function format()
    {
        return $this;
        
    }
}
