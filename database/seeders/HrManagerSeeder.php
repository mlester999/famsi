<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class HrManagerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $userIds = \App\Models\User::factory(100)->create(['user_type' => 2])->pluck('id')->toArray();

        \App\Models\HrManager::factory(100)->create([
            'user_id' => function () use (&$userIds) {
                return array_shift($userIds);
            },
        ]);
    }
}
