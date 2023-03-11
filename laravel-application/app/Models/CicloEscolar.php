<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CicloEscolar extends Model
{
    use HasFactory;

    protected $table = 'ciclo_escolar';
    protected $fillable = [
        'id',
        'fecha_ingreso',
        'fecha_salida'
    ];

    //TODO: PENDIENTE TESTEAR
    public function usuarios() : HasMany
    {
        return $this->hasMany(Usuario::class, "id_ciclo_escolar", "id");
    }

    public function solicitudesUsuario() : HasMany
    {
        return $this->hasMany(SolicitudUsuario::class, "id_ciclo_escolar", "id");
    }

    public function format()
    {
        return $this;
        
    }
}
