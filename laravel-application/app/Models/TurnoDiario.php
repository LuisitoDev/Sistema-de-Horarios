<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TurnoDiario extends Model
{
    //TODO: PENDIENTE CAMBIAR NOMBRE DE COLUMNA "id_dia" A "id_dia"
    use HasFactory;

    public const table_name = "turnos_diarios";
    public const id = "id";
    public const hora_entrada = "hora_entrada";
    public const hora_salida = "hora_salida";
    public const dia = "dia";
    public const id_horario = "id_horario";

    protected $table = self::table_name;
    protected $fillable = [
        self::id,
        self::hora_entrada,
        self::hora_salida,
        self::dia,
        self::id_horario
    ];

    public function horario() : BelongsTo {
        return $this->belongsTo(Horario::class, self::id_horario, Horario::id);
    }

    public function dia_tabla() : BelongsTo {
        return $this->belongsTo(Dia::class, self::dia, Dia::id);
    }

    public function format()
    {
        return $this;
    }
}
