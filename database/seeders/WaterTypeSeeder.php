<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\I013tTiposAgua;

class WaterTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        I013tTiposAgua::create([
            'tx_tipo_agua' => 'Agua de Pozo',
            'code' => 'pozo',
            'description' => 'Sistema de agua proveniente de pozo',
            'is_active' => true
        ]);

        I013tTiposAgua::create([
            'tx_tipo_agua' => 'Agua de Ciudad',
            'code' => 'ciudad',
            'description' => 'Sistema de agua proveniente de la ciudad',
            'is_active' => true
        ]);
    }
} 