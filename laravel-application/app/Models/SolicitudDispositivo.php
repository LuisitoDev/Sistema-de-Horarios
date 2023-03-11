<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class SolicitudDispositivo extends Model
{
    use HasFactory;
    use SoftDeletes; //TODO: ES NECESARIO TENER AQUI SOFT DELETES?
    
    protected $table = 'solicitudes_dispositivos';
    protected $fillable = [
        'id',
        'direccion_mac_dispositivo',
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
