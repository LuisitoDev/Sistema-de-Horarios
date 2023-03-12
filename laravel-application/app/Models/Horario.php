<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Horario extends Model
{
    use HasFactory;
    
    public const table_name = "horarios";
    public const id = "id";
    public const id_usuario = "id_usuario";

    protected $table = self::table_name;
    protected $fillable = [
        self::id,
        self::id_usuario
    ];

    public function usuario() : BelongsTo{
        return $this->belongsTo(Usuario::class, self::id_usuario, Usuario::id);
    }

    public function turnosDiarios() : HasMany {
        return $this->hasMany(TurnoDiario::class, TurnoDiario::id_horario, self::id);
    }

    public function format()
    {
        return $this;
    }
}
