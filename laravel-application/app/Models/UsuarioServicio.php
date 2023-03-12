<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UsuarioServicio extends Model
{
    use HasFactory;

    public const table_name = "usuarios_servicios";
    public const id_usuario = "id_usuario";
    public const id_servicio = "id_servicio";

    protected $table = self::table_name;
    protected $fillable = [
        self::id_usuario,
        self::id_servicio
    ];

    public function format()
    {
        return $this;
    }
}
