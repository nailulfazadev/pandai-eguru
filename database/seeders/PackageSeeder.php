<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PackageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\Package::create([
            'name' => 'Paket Semester',
            'slug' => 'semester',
            'price' => 89000,
            'duration_days' => 180,
            'features' => [
                'Buka **semua tools** AI Guru',
                'Limit generate hingga **10x per hari**',
                'Prioritas server AI (lebih cepat)'
            ],
            'is_active' => true,
        ]);

        \App\Models\Package::create([
            'name' => 'Paket Tahunan',
            'slug' => 'tahunan',
            'price' => 147000,
            'duration_days' => 365,
            'features' => [
                'Semua fitur di Paket Semester',
                'Lebih hemat Rp 31.000 / tahun',
                'Akses ke fitur beta duluan'
            ],
            'is_active' => true,
        ]);
    }
}
