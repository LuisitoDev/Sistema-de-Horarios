<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TurnoDiario extends Model
{
    //TODO: PENDIENTE CAMBIAR NOMBRE DE COLUMNA "id_dia" A "id_dia"
    use HasFactory;

    protected $table = 'turnos_diarios';
    protected $fillable = [
        'id',
        'hora_entrada',
        'hora_salida',
        'dia',
        'id_horario'
    ];

    public function horario() : BelongsTo {
        return $this->belongsTo(Horario::class, 'id_horario', 'id');
    }

    public function dia_tabla() : BelongsTo {
        return $this->belongsTo(Dia::class, 'dia', 'id');
    }
}
