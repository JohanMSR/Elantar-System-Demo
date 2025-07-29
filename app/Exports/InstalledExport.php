<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class InstalledExport implements FromCollection, WithHeadings
{
    
    protected $colection;

    public function __construct(array $colection){
        $this->colection = $colection;
    }

   public function collection()
    {
        $myColection = collect($this->colection);
        return $myColection;
     
    }
    public function headings(): array
    {
        return [
            'Analista', 
            'Oficina',
            'Código Aplicaciones',
            'Ventas',
            'Monto'
        ];
    }    
}
