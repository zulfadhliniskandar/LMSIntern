<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use App\Models\LearningMaterials;
use App\Jobs\ProcessLearningMaterial;
use Illuminate\Support\Facades\Storage;

$user = User::where('email', 'zulfadhliniskandar2123@gmail.com')->first();
$studentId = $user->student->student_id;

echo "Simulating PDF Upload for Student ID: {$studentId}\n";

// Create a dummy PDF or text file
$pdfPath = 'learning-materials/sample_lecture.pdf';
Storage::disk('local')->put($pdfPath, "%PDF-1.4 sample PDF content for testing OOP Chapter 5 selection statements.");

try {
    $material = LearningMaterials::create([
        'student_id' => $studentId,
        'title' => 'OOP Chapter 5 Selection PDF',
        'description' => 'Test PDF upload',
        'original_file_path' => $pdfPath,
        'original_filename' => 'OOP_Chapter05_Selection.pdf',
        'file_type' => 'pdf',
        'file_size' => 1024,
        'simplification_status' => 'processing',
    ]);
    echo "SUCCESS: LearningMaterials DB record created ID: {$material->id}\n";

    ProcessLearningMaterial::dispatchSync($material);
    $material->refresh();

    echo "Status: {$material->simplification_status}\n";
    echo "Extracted Text length: " . strlen($material->extracted_text) . "\n";
    echo "Simplified Text length: " . strlen($material->simplified_text) . "\n";
    echo "PDF Path: {$material->simplified_pdf_path}\n";
} catch (\Exception $e) {
    echo "EXCEPTION: " . $e->getMessage() . "\n";
}
