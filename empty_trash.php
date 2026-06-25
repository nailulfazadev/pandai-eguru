<?php
require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

try {
    $credentialsPath = storage_path('app/google/credentials.json');
    
    $client = new \Google_Client();
    $client->setAuthConfig($credentialsPath);
    $client->addScope(\Google_Service_Drive::DRIVE);

    $driveService = new \Google_Service_Drive($client);

    echo "Emptying trash for Service Account Drive...\n";
    $driveService->files->emptyTrash();
    echo "Trash emptied!\n";

    $about = $driveService->about->get(['fields' => 'storageQuota']);
    $quota = $about->getStorageQuota();
    echo "Limit: " . $quota->getLimit() . "\n";
    echo "Usage: " . $quota->getUsage() . "\n";

} catch (\Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
}
