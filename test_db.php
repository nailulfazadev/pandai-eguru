<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$docs = App\Models\Document::orderBy('id', 'desc')->take(2)->get();
foreach ($docs as $doc) {
    echo "ID: " . $doc->id . "\n";
    echo "Type: " . $doc->type . "\n";
    echo "Mock: " . $doc->is_mock . "\n";
    echo "Content:\n" . substr($doc->content, 0, 500) . "\n...\n";
    echo str_repeat('-', 40) . "\n";
}
