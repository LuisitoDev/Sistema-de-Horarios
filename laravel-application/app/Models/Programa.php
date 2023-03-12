<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Programa extends Model
{
    use HasFactory;

    public const table_name = "programas";
    public const id = "id";
    public const nombre = "nombre";

    protected $table = self::table_name;
    protected $fillable = [
        self::id,
        self::nombre
    ];

    public function usuarios() : BelongsToMany
    {
        return $this->belongsToMany(Usuario::class, UsuarioPrograma::class, UsuarioPrograma::id_programa, UsuarioPrograma::id_usuario);
    }

    public function solicitudUsuario() : HasOne
    {
        return $this->hasOne(SolicitudUsuario::class, self::id, SolicitudUsuario::id_programa_usuario);
    }

    public function format()
    {
        return $this;
    }
}
