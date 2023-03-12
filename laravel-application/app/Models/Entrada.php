<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Entrada extends Model
{
    use HasFactory;

    public const table_name = "entradas";
    public const id = "id";
    public const hora_entrada_programada = "hora_entrada_programada";
    public const hora_salida_programada = "hora_salida_programada";
    public const horas_realizadas_programada = "horas_realizadas_programada";
    public const hora_entrada = "hora_entrada";
    public const hora_salida = "hora_salida";
    public const horas_realizadas = "horas_realizadas";
    public const reporte_diario = "reporte_diario";
    public const id_status = "id_status";
    public const id_usuario = "id_usuario";

    protected $table = self::table_name;    
    protected $fillable = [
        self::id,
        self::hora_entrada_programada,
        self::hora_salida_programada,
        self::horas_realizadas_programada,
        self::hora_entrada,
        self::hora_salida,
        self::horas_realizadas,
        self::reporte_diario,
        self::id_status,
        self::id_usuario
    ];

    public function scopeFindWhereUsuario($query, $id_usuario)
    {
        return $query->where(self::id_usuario, $id_usuario);
    }

    public function status() : BelongsTo{
        return $this->belongsTo(Status::class, self::id_status, Status::id);
    }

    public function usuario() : BelongsTo{
        return $this->belongsTo(Usuario::class, self::id_usuario, Usuario::id);
    }

    public function format()
    {
        return $this;
    }
}
