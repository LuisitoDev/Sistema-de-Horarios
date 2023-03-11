<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Programa extends Model
{
    use HasFactory;

    protected $table = 'programas';
    protected $fillable = [
        'id',
        'nombre'
    ];

    public function usuarios() : BelongsToMany
    {
        return $this->belongsToMany(Usuario::class, UsuarioPrograma::class, "id_programa", "id_usuario");
    }

    public function solicitudUsuario() : HasOne
    {
        return $this->hasOne(SolicitudUsuario::class, "id", "id_programa_usuario");
    }
}
