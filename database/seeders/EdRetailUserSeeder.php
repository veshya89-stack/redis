<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class EdRetailUserSeeder extends Seeder
{
    /**
     * Membuat 1 akun bersama untuk tim ED Retail.
     * GANTI email & password di bawah ini sebelum dijalankan,
     * lalu bagikan kredensialnya ke tim ED Retail secara internal.
     */
    public function run(): void
    {
        User::updateOrCreate(
    ['email' => 'edretail.pln@gmail.com'],
    [
        'name'     => 'Tim ED Retail',
        'password' => Hash::make('EdRetail2026!'),
    ]
);
    }
}
