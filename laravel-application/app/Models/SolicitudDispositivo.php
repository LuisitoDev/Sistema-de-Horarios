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

    public const table_name = "solicitudes_dispositivos";
    public const id = "id";
    public const direccion_mac_dispositivo = "direccion_mac_dispositivo";
    public const id_usuario = "id_usuario";

    public const created_at = "created_at";
    public const updated_at = "updated_at";
    public const deleted_at = "deleted_at";
    
    protected $table = self::table_name;
    protected $fillable = [
        self::id,
        self::direccion_mac_dispositivo,
        self::id_usuario
    ];

    //TODO: RELACION - PENDIENTE PROBAR
    public function usuario() : BelongsTo{
        return $this->belongsTo(Usuario::class, self::id_usuario, Usuario::id);
    }

    public function format()
    {
        return $this;
    }
}
