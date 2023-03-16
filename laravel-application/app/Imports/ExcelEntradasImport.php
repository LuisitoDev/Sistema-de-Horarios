<?php

namespace App\Imports;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;


class ExcelEntradasImport implements WithMultipleSheets
{
    public function sheets(): array
    {
        return [
            0 => new UsuariosEntradasImport(),
        ];
    }
}


/*
<?php

namespace App\Imports;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use App\Repositories\Entrada\EntradaRepository;


class ExcelEntradasImport implements WithMultipleSheets
{
    private $entradaRepository;

    public function __construct(
        EntradaRepository $entradaRepository)
    {
        $this->entradaRepository = $entradaRepository;
    }

    public function sheets(): array
    {
        return [
            0 => new UsuariosEntradasImport($this->entradaRepository),
        ];
    }
}

*/