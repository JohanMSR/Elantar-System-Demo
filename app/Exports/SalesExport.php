<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use DragonCode\Contracts\Cashier\Http\Request;
use Maatwebsite\Excel\Concerns\WithHeadings;

class SalesExport implements FromCollection, WithHeadings
{
    
    protected $sales;

    public function __construct(array $sales){
        $this->sales = $sales;
    }

   public function collection()
    {
        $mySales = collect($this->sales);
        return $mySales;
    }
    public function headings(): array
    {
        return [
            'ID', 
            'Numero de teléfono',
            'Agendador',
            'Analista',
            'Estado', 
            'Fecha estatus actual',
            'Estatus actual',
            'Fecha de creacion',
            'Precio Total',
            'Total ventas'
        ];
    }    
}
