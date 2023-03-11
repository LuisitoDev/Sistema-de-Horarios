<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Status extends Model
{
    use HasFactory;

    public $incrementing = false;
    protected $table = 'status';
    protected $fillable = [
        'id',
        'nombre'
    ];

    public function entradas() : HasMany
    {
        return $this->hasMany(Entrada::class, "id_status", "id");
    }
}
