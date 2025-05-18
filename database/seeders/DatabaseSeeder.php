<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        User::factory()->create([
            'name' => 'User 1',
            'email' => 'user1@user1.com',
            'password' => Hash::make('password')
        ]);
        User::factory()->create([
            'name' => 'User 2',
            'email' => 'user2@user.com',
            'password' => Hash::make('password')
        ]);

         User::factory(20)->create();
    }
}
