<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Dispositivo extends Model
{
    use HasFactory;

    public const table_name = "dispositivos";
    public const id = "id";
    public const direccion_mac = "direccion_mac";
    public const id_usuario = "id_usuario";

    protected $table = self::table_name;
    protected $fillable = [
        self::id,
        self::direccion_mac,
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
