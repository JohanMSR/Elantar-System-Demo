<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ProjectsExport implements FromCollection, WithHeadings
{
    protected $projects;

    public function __construct(array $projects)
    {
        $this->projects = $projects;
    }

    public function collection()
    {
        $projects = collect($this->projects);
        return $projects;
    }
    public function headings(): array
    {
        return [
            'ID', 
            'Fecha de Creacion',
            'Agendador',
            'Analista',
            'Estado',
            'Ciudad', 
            'Fecha Estatus Actual',
            'Estatus Actual',
            'Estatus Siguiente',            
            'Monto'
        ];
    }    
}
