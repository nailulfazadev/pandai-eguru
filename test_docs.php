<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$docs = App\Models\Document::orderBy('id', 'desc')->take(3)->get(['id', 'type', 'is_mock', 'created_at']);
echo json_encode($docs, JSON_PRETTY_PRINT);
