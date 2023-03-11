<?php

namespace App\Exports;

use App\Exports\Sheets\UsuariosDataSheet;
use App\Exports\Sheets\UsuariosHorariosSheet;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class UsuariosExport implements WithMultipleSheets
{
    use Exportable;

    private $dayFrom;
    private $dayTo;

    public function __construct($dayFrom, $dayTo)
    {
        $this->dayFrom = $dayFrom;
        $this->dayTo = $dayTo;
    }

    public function sheets(): array
    {
        $sheets = [];
        $sheets[0] = new UsuariosDataSheet($this->dayFrom, $this->dayTo);
        $sheets[1] = new UsuariosHorariosSheet($this->dayFrom, $this->dayTo);

        return $sheets;
    }
}
