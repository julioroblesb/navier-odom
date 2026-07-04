<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SuperAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\User::updateOrCreate(
            ['email' => 'julioroblesb13@gmail.com'],
            [
                'name' => 'Julio Robles',
                'password' => bcrypt('Skyrote13'),
                'is_super_admin' => true,
                // Ensure tenant_id is null for the super admin
                'tenant_id' => null,
            ]
        );
    }
}
