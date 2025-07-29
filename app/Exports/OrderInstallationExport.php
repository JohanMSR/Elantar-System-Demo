<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class OrderInstallationExport implements FromCollection, WithHeadings
{
    protected $orders;

    public function __construct(array $orders)
    {
        $this->orders = $orders;
    }

    public function collection()
    {
        return collect($this->orders);
    }
    public function headings(): array
    {
        return [
            'ID', 
            'Telefono',
            'Accion',
            'Total Gastos',
            'Estatus de la Orden',            
            'Manager',
            'Instalador',            
            'Fecha de Instalacion'
        ];
    }    
}
