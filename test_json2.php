<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$schedules = App\Models\Schedule::where('day_of_week', 'Rabu')->get();
echo json_encode($schedules, JSON_PRETTY_PRINT);
