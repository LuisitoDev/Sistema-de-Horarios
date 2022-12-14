<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TareaProgramada extends Model
{
    use HasFactory;

    protected $table = 'tarea_programada';
    protected $fillable = [
        'nombre_tarea' 
    ];
}
