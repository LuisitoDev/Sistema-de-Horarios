<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array<int, string>
     */
    protected $except = [
        // '/entrada/registrar-hora-entrada',
        // '/entrada/registrar-hora-salida'
        //'/admin/alumnos-entradas/cant/{elements}/pag/{page}/tuition/{tuition}/fecha-desde/{dayFrom}/fecha-hasta/{dayTo}'
    ];
}
