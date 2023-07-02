<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $adminUser = \App\Models\User::factory()->create([
                'email' => 'admin@admin.com',
                'email_verified_at' => now(),
                'password' => bcrypt('12345678'),
                'user_type' => 3,
                'created_at' => now(),
                'updated_at' => now()
            ]);

        \App\Models\Admin::factory()->create([
                'user_id' => $adminUser->id,
                'first_name' => 'Admin',
                'last_name' => 'Famsi',
                'created_at' => now(),
                'updated_at' => now()
        ]);
    }
}
