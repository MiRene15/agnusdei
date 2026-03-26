<?php

namespace Database\Seeders;

use App\Models\RoleReferenceCode;
use Illuminate\Database\Seeder;

class RoleReferenceCodeSeeder extends Seeder
{
    public function run(): void
    {
        $codes = [
            [
                'role' => 'admin',
                'code' => 'ADMIN-' . now()->year . '-001',
                'description' => 'Default admin code',
                'is_active' => true,
                'max_uses' => 5,
                'used_count' => 0,
            ],
            [
                'role' => 'registrar',
                'code' => 'REGISTRAR-' . now()->year . '-001',
                'description' => 'Default registrar code',
                'is_active' => true,
                'max_uses' => 5,
                'used_count' => 0,
            ],
            [
                'role' => 'cashier',
                'code' => 'CASHIER-' . now()->year . '-001',
                'description' => 'Default cashier code',
                'is_active' => true,
                'max_uses' => 2,
                'used_count' => 0,
            ],
            [
                'role' => 'teacher',
                'code' => 'TEACHER-' . now()->year . '-001',
                'description' => 'Default teacher code',
                'is_active' => true,
                'max_uses' => 50,
                'used_count' => 0,
            ],
        ];

        foreach ($codes as $code) {
            RoleReferenceCode::updateOrCreate(
                ['code' => $code['code']],
                $code
            );
        }
    }
}