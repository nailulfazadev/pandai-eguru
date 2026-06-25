<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$schedules = App\Models\Classroom::find(1)->schedules()->orderBy('day_of_week')->orderBy('start_time')->get();
echo json_encode($schedules, JSON_PRETTY_PRINT);
