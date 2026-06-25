<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$schedules = App\Models\Schedule::where('classroom_id', 2)->get();
echo json_encode($schedules, JSON_PRETTY_PRINT);
