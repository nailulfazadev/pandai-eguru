<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Periksa apakah admin sudah ada
        if (!User::where('email', 'admin@pandai.com')->exists()) {
            User::create([
                'name' => 'Super Admin',
                'email' => 'admin@pandai.com',
                'password' => Hash::make('password123'),
                'is_admin' => true,
                'subscription_ends_at' => now()->addYears(10), // Admin selalu aktif
            ]);
        }
    }
}
