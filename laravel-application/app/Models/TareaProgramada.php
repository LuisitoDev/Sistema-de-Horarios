<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TareaProgramada extends Model
{
    use HasFactory;

    public const table_name = "tarea_programada";
    public const id = "id";
    public const nombre_tarea = "nombre_tarea";

    protected $table = self::table_name;
    protected $fillable = [
        self::id,
        self::nombre_tarea
    ];

    public function tareasEjecucion() : HasMany
    {
        return $this->hasMany(TareaEjecucion::class, TareaEjecucion::id_tarea_programada, self::id);
    }

    public function format()
    {
        return $this;
    }
}
