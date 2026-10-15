<?php
require __DIR__ . '/../../vendor/autoload.php';
$app = require_once __DIR__ . '/../../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$controller = new App\Http\Controllers\GenerateFeedbackController();
$response = $controller->generateFeedback(64);

$pdfRaw = $response->getContent();
echo "PDF generated successfully, length: " . strlen($pdfRaw) . "\n";
