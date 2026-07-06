<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::firstOrCreate(
            ['email' => 'admin@pln.co.id'],
            [
                'name' => 'Bidang Eksekutif Direksi',
                'password' => bcrypt('password'),
                'role' => 'admin',
                'jabatan' => 'Admin Bidang Eksekutif Direksi',
            ]
        );

        User::firstOrCreate(
            ['email' => 'direktur.retail@pln.co.id'],
            [
                'name' => 'Direktur Retail dan Niaga',
                'password' => bcrypt('password'),
                'role' => 'viewer',
                'jabatan' => 'Direktur Retail dan Niaga',
            ]
        );

        $this->call([
            StrategicInitiativeSeeder::class,
        ]);
    }
}
