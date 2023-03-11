<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Carrera extends Model
{
    use HasFactory;
    
    protected $table = 'carreras';
    protected $fillable = [
        'id',
        'nombre',
        'abreviacion'
    ];

    public function usuarios() : HasMany
    {
        return $this->hasMany(Usuario::class, "id_carrera", "id");
    }

    public function solicitudesUsuario() : HasMany
    {
        return $this->hasMany(SolicitudUsuario::class, "id_carrera", "id");
    }
}
