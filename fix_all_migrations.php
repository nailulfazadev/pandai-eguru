<?php
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Artisan;

// 1. Matikan FK checks & drop tables
DB::statement('SET FOREIGN_KEY_CHECKS=0;');
Schema::dropIfExists('attendances');
Schema::dropIfExists('journals');
Schema::dropIfExists('schedules');
Schema::dropIfExists('students');
Schema::dropIfExists('classrooms');

// 2. Bersihkan seluruh catatan di tabel migrations untuk ke-5 tabel ini
DB::table('migrations')->where('migration', 'like', '%classrooms%')->delete();
DB::table('migrations')->where('migration', 'like', '%students%')->delete();
DB::table('migrations')->where('migration', 'like', '%schedules%')->delete();
DB::table('migrations')->where('migration', 'like', '%journals%')->delete();
DB::table('migrations')->where('migration', 'like', '%attendances%')->delete();
DB::statement('SET FOREIGN_KEY_CHECKS=1;');

// 3. Bersihkan file untracked dengan memanggil shell command git clean
exec('git clean -fd database/migrations/');
exec('git checkout database/migrations/');

// 4. Jalankan migrasi
echo "Menjalankan migrasi bersih...\n";
Artisan::call('migrate', ['--force' => true]);
echo Artisan::output();
echo "\nSelesai!\n";
