<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Entrada extends Model
{
    use HasFactory;

    protected $table = 'entradas';
    protected $fillable = [
        'id',
        'hora_entrada_programada',
        'hora_salida_programada',
        'horas_realizadas_programada',
        'hora_entrada',
        'hora_salida',
        'horas_realizadas',
        'reporte_diario',
        'id_status',
        'id_usuario'
    ];

    public function status() : BelongsTo{
        return $this->belongsTo(Status::class, 'id_status', 'id');
    }

    public function usuario() : BelongsTo{
        return $this->belongsTo(Usuario::class, 'id_usuario', 'id');
    }
}
