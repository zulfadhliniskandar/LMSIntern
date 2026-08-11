<?php

namespace App\Jobs;

use App\Models\LearningMaterials;
use App\Models\LearningMaterialSummaries;
use App\Services\TextExtractionService;
use App\Services\TextSimplificationService;
use App\Services\PdfGenerationService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class ProcessLearningMaterial implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(private LearningMaterials $learningMaterial)
    {
    }

    public function handle(): void
    {
        try {
            $this->learningMaterial->update([
                'simplification_status' => 'processing',
            ]);

            $extractionService = new TextExtractionService();
            $simplificationService = new TextSimplificationService();
            $pdfService = new PdfGenerationService();

            // 1. Extract text from uploaded document
            $extractedText = $extractionService->extractText($this->learningMaterial->original_file_path);
            $origWordCount = $extractionService->countWords($extractedText);

            // 2. Assess original readability
            $origReadability = $simplificationService->assessReadability($extractedText);

            // 3. Process simplification, key points & summary in a single AI call
            $aiResults = $simplificationService->processFullContent($extractedText);
            $simplifiedText = $aiResults['simplified_text'];
            $keyPoints = $aiResults['key_points'];
            $summaryText = $aiResults['summary'];

            $simpWordCount = $extractionService->countWords($simplifiedText);

            // 4. Assess simplified readability
            $simpReadability = $simplificationService->assessReadability($simplifiedText);

            // Calculate percentage reduction
            $reduction = $origWordCount > 0 
                ? max(0, round((($origWordCount - $simpWordCount) / $origWordCount) * 100, 2))
                : 0;

            // 5. Update material record
            $this->learningMaterial->update([
                'extracted_text' => $extractedText,
                'simplified_text' => $simplifiedText,
                'original_word_count' => $origWordCount,
                'simplified_word_count' => $simpWordCount,
                'simplification_percentage' => $reduction,
                'original_reading_level' => $origReadability['level'],
                'original_readability_score' => $origReadability['score'],
                'simplified_reading_level' => $simpReadability['level'],
                'simplified_readability_score' => $simpReadability['score'],
            ]);

            // 6. Save Summary & Key Points
            LearningMaterialSummaries::updateOrCreate(
                ['learning_materials_id' => $this->learningMaterial->id],
                [
                    'key_points' => $keyPoints,
                    'summary' => $summaryText,
                ]
            );

            // Reload relationships before PDF generation
            $this->learningMaterial->load('summary');

            // 7. Render and save PDF
            $pdf = $pdfService->generateSimplifiedPdf($this->learningMaterial);
            $pdfFilename = 'simplified_' . $this->learningMaterial->id . '_' . time() . '.pdf';
            $pdfRelativePath = 'simplified-materials/' . $pdfFilename;

            Storage::disk('local')->put($pdfRelativePath, $pdf->output());

            $this->learningMaterial->update([
                'simplified_pdf_path' => $pdfRelativePath,
                'simplification_status' => 'completed',
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to process learning material #' . $this->learningMaterial->id . ': ' . $e->getMessage());
            $this->learningMaterial->update([
                'simplification_status' => 'failed',
            ]);
        }
    }
}
