<?php
require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

try {
    $credentialsPath = storage_path('app/google/credentials.json');
    
    if (!file_exists($credentialsPath)) {
        die("Error: credentials.json not found.\n");
    }

    $client = new \Google_Client();
    $client->setAuthConfig($credentialsPath);
    $client->addScope(\Google_Service_Drive::DRIVE);

    $driveService = new \Google_Service_Drive($client);

    echo "Fetching files from Service Account Drive...\n";
    
    $optParams = [
        'pageSize' => 100,
        'fields' => 'nextPageToken, files(id, name)'
    ];
    
    $deletedCount = 0;
    
    do {
        $results = $driveService->files->listFiles($optParams);
        
        foreach ($results->getFiles() as $file) {
            $id = $file->getId();
            $name = $file->getName();
            
            if ($id === env('GOOGLE_SLIDES_TEMPLATE_ID')) {
                echo "Skipping Master Template: $name\n";
                continue;
            }
            
            echo "Deleting: $name ($id)\n";
            $driveService->files->delete($id);
            $deletedCount++;
        }
        
        $optParams['pageToken'] = $results->getNextPageToken();
    } while ($optParams['pageToken']);

    echo "Done! Deleted $deletedCount files from the Service Account Drive.\n";

} catch (\Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
}
