<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TareaProgramada extends Model
{
    use HasFactory;

    protected $table = 'tarea_programada';
    protected $fillable = [
        'id',
        'nombre_tarea' 
    ];

    public function tareasEjecucion() : HasMany
    {
        return $this->hasMany(TareaEjecucion::class, "id_tarea_programada", "id");
    }
}
