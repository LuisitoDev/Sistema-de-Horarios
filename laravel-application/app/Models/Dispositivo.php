<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Dispositivo extends Model
{
    use HasFactory;

    protected $table = 'dispositivos';
    protected $fillable = [
        'id',
        'direccion_mac',
        'id_usuario'
    ];

    //TODO: RELACION - PENDIENTE PROBAR
    public function usuario() : BelongsTo{
        return $this->belongsTo(Usuario::class, 'id_usuario', 'id');
    }

    public function format()
    {
        return $this;
    }
}
