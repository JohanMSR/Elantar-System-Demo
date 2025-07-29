<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ExportTeam implements FromCollection, WithHeadings
{
    
    protected $team;

    public function __construct(array $team){
        $this->team = $team;
    }

   public function collection()
    {
        $myTeam = collect($this->team);
        return $myTeam;
     
    }
    public function headings(): array
    {
        return [
            'Equipo', 
            'Rol',
            'Oficina'
        ];
    }    
}
