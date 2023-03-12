<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TareaEjecucion extends Model
{
    use HasFactory;

    public const table_name = "tarea_ejecucion";
    public const id = "id";
    public const hora_ejecucion = "hora_ejecucion";
    public const id_tarea_programada = "id_tarea_programada";

    protected $table = self::table_name;

    protected $fillable = [
        self::id,
        self::hora_ejecucion,
        self::id_tarea_programada 
    ];

    public function tarea_programada() : BelongsTo{
        return $this->belongsTo(TareaProgramada::class, self::id_tarea_programada, TareaProgramada::id);
    }

    public function format()
    {
        return $this;
    }
}
