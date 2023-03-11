<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Rotacion extends Model
{
    use HasFactory;

    protected $table = 'rotaciones';
    protected $fillable = [
        'id',
        'lunes_presencial',
        'martes_presencial',
        'miercoles_presencial',
        'jueves_presencial',
        'viernes_presencial'
    ];

    public function usuarios() : HasMany
    {
        return $this->hasMany(Usuario::class, "id_rotacion", "id");
    }
}
