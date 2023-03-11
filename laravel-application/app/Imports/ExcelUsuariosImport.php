<?php

namespace App\Imports;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class ExcelUsuariosImport implements WithMultipleSheets
{

    public function sheets(): array
    {
        return [
            0 => new UsuariosImport(),
            1 => new HorariosImport(),
        ];
    }
}
