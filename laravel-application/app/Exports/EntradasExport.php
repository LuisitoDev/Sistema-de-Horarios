<?php

namespace App\Exports;

use App\Exports\Sheets\EntradasInformeFinalHorasSheet;
use App\Exports\Sheets\EntradasInformeGeneralSheet;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class EntradasExport implements WithMultipleSheets
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
        $sheets[0] = new EntradasInformeFinalHorasSheet($this->dayFrom, $this->dayTo);
        $sheets[1] = new EntradasInformeGeneralSheet($this->dayFrom, $this->dayTo);

        return $sheets;
    }
}
