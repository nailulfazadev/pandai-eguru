<?php
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Artisan;

// 1. Matikan FK checks
DB::statement('SET FOREIGN_KEY_CHECKS=0;');

// 2. Hapus tabel secara paksa
Schema::dropIfExists('attendances');
Schema::dropIfExists('journals');
Schema::dropIfExists('schedules');
Schema::dropIfExists('students');
Schema::dropIfExists('classrooms');

// 3. Bersihkan migrations
DB::table('migrations')->where('migration', 'like', '%classrooms%')->delete();
DB::table('migrations')->where('migration', 'like', '%students%')->delete();
DB::table('migrations')->where('migration', 'like', '%schedules%')->delete();
DB::table('migrations')->where('migration', 'like', '%journals%')->delete();
DB::table('migrations')->where('migration', 'like', '%attendances%')->delete();

// 4. Nyalakan FK checks
DB::statement('SET FOREIGN_KEY_CHECKS=1;');

// 5. Jalankan migrasi
echo "Menjalankan migrasi...\n";
Artisan::call('migrate', ['--force' => true]);
echo Artisan::output();
echo "\nSelesai!\n";
