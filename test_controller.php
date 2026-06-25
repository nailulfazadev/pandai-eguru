<?php
require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$request = \Illuminate\Http\Request::create('/tools/export-google-slides', 'POST', [
    'topic' => 'Test Topic',
    'slides' => [
        [
            'title' => 'Cover Slide',
            'bullets' => ['Subject', 'Grade', 'Author']
        ],
        [
            'title' => 'Content Slide 1',
            'bullets' => ['Bullet 1', 'Bullet 2']
        ],
        [
            'title' => 'Content Slide 2',
            'bullets' => ['Bullet 3', 'Bullet 4']
        ]
    ]
]);

$controller = app()->make(\App\Http\Controllers\GeminiController::class);
$response = $controller->exportToGoogleSlides($request);

echo $response->getContent();
