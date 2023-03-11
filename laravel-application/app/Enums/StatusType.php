<?php

namespace App\Enums;

abstract class StatusType
{
    const CUMPLIO_ENTRADA = 1;
    const NO_CUMPLIO_ENTRADA = 2;
    const CUMPLIO_ENTRADA_INCOMPLETA = 3;
    const TRABAJANDO = 4;
    const CERRO_TARDE = 5;
    const NO_MARCO_SALIDA = 6;
    const CUMPLIO_ONLINE = 7;
}
