<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TareaEjecucion extends Model
{
    use HasFactory;

    protected $table = 'tarea_ejecucion';

    protected $fillable = [
        'id',
        'hora_ejecucion',
        'id_tarea_programada' 
    ];

    public function tarea_programada() : BelongsTo{
        return $this->belongsTo(TareaProgramada::class, 'id_tarea_programada', 'id');
    }
}
