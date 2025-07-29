<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UsersDevelopersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $User = [
            [
                'id' => '1',
                'name' => 'Enrique',
                'surname' => 'Rivero',
                'email' => 'enriquerivero1974@gmail.com',
                'password' => Hash::make('#enrique.')
            ],
            [
                'id' => '2',
                'name' => 'Miguel',
                'surname' => 'Silva',
                'email' => 'migueljsilvas@gmail.com',
                'password' => Hash::make('#miguel.')
            ],
            [
                'id' => '3',
                'name' => 'Daniel',
                'surname' => 'Rengifo',
                'email' => 'rengifod@gmail.com',
                'password' => Hash::make('#daniel.')
            ],
            [
                'id' => '4',
                'name' => 'Maikol',
                'surname' => 'Isava',
                'email' => 'maikol.isava@gmail.com',
                'password' => Hash::make('#maikol.')
            ],
            [
                'id' => '5',
                'name' => 'Johan',
                'surname' => 'Salazar',
                'email' => 'johanmsalazarr@gmail.com',
                'password' => Hash::make('#johan.')
            ]

        ];

        foreach ($User as $Usuarios) {
            $userdb = User::find($Usuarios['id']);
            if (!$userdb) {
                User::create($Usuarios);
                
            }
        }
    }
}
