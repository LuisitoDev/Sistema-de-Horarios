<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Horario extends Model
{
    use HasFactory;
    
    protected $table = 'horarios';
    protected $fillable = [
        'id',
        'id_usuario'
    ];

    public function usuario() : BelongsTo{
        return $this->belongsTo(Usuario::class, 'id_usuario', 'id');
    }

    public function turnosDiarios() : HasMany {
        return $this->hasMany(TurnoDiario::class, 'id_horario', 'id');
    }
}
