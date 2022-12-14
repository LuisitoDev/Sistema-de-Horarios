<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rotacion extends Model
{
    use HasFactory;

    protected $table = 'rotaciones';
    protected $fillable = [
        'lunes_presencial',
        'martes_presencial',
        'miercoles_presencial',
        'jueves_presencial',
        'viernes_presencial'
    ];
}
