<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Servicio extends Model
{
    use HasFactory;

    protected $table = 'servicios';
    protected $fillable = [
        'id',
        'nombre',
        'horas_totales',
        'horas_por_dia'
    ];

    public function usuarios() : BelongsToMany
    {
        return $this->belongsToMany(Usuario::class, UsuarioServicio::class, "id_servicio", "id_usuario");
    }

    public function solicitudUsuario() : HasOne
    {
        return $this->hasOne(SolicitudUsuario::class, "id", "id_servicio_usuario");
    }
}
