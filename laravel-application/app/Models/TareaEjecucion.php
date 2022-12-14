<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TareaEjecucion extends Model
{
    use HasFactory;

    protected $table = 'tarea_ejecucion';

    protected $fillable = [
        'hora_ejecucion',
        'id_tarea_programada' 
    ];

    public function id_tarea_programada(){
        return $this->hasOne(TareaProgramada::class, 'id', 'id_tarea_programada');
    }
}
