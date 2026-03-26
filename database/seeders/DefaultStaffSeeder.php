<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DefaultStaffSeeder extends Seeder
{
    public function run(): void
    {
        $staff = [
            [
                'name' => 'System Admin',
                'email' => 'admin@agnusdei.local',
                'contact_number' => '09170000001',
                'role' => 'admin',
                'password' => Hash::make('Agnus2026!'),
            ],
            [
                'name' => 'Default Registrar',
                'email' => 'registrar@agnusdei.local',
                'contact_number' => '09170000002',
                'role' => 'registrar',
                'password' => Hash::make('Agnus2026!'),
            ],
            [
                'name' => 'Default Cashier',
                'email' => 'cashier@agnusdei.local',
                'contact_number' => '09170000003',
                'role' => 'cashier',
                'password' => Hash::make('Agnus2026!'),
            ],
        ];

        foreach ($staff as $user) {
            User::updateOrCreate(
                ['email' => $user['email']],
                $user
            );
        }
    }
}
