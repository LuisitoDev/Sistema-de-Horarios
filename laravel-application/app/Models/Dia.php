<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Dia extends Model
{
    public const table_name = "dias";
    public const id = "id";
    public const nombre = "nombre";

    protected $table = self::table_name;
    protected $fillable = [
        self::id,
        self::nombre
    ];

    public function turnoDiario() : HasOne {
        return $this->hasOne(TurnoDiario::class, TurnoDiario::dia, self::id);
    }

    public function format()
    {
        return $this;
    }
}
