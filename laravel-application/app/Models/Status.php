<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Status extends Model
{
    use HasFactory;

    public const table_name = "solicitudes_dispositivos";
    public const id = "id";
    public const nombre = "nombre";

    public $incrementing = false;
    protected $table = self::table_name;
    protected $fillable = [
        self::id,
        self::nombre
    ];

    public function entradas() : HasMany
    {
        return $this->hasMany(Entrada::class, Entrada::id_status, self::id);
    }

    public function format()
    {
        return $this;
    }
}
