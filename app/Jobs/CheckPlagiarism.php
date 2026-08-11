<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use App\Models\AssignmentSubmission;
use App\Models\check_plagiarism;
use App\Models\AiDetection;
use App\Services\AiDetectionService;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;

class CheckPlagiarism implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(private AssignmentSubmission $submission) {}

    public function handle(): void
    {
        $check = check_plagiarism::updateOrCreate(
            ['assignment_submissions_id' => $this->submission->id],
            ['status' => 'checking', 'notes' => null]
        );

        try {
            $fileContent = $this->extractAllTextFromSubmission($this->submission);

            if (!$fileContent || trim($fileContent) === '') {
                $check->update([
                    'status' => 'failed',
                    'similarity_percentage' => 0,
                    'notes' => 'No readable text content could be extracted from the uploaded file(s).',
                ]);
                return;
            }

            $assignmentId = $this->submission->assignment_id;
            $previousSubmissions = AssignmentSubmission::with(['student.user'])
                ->where('assignment_id', $assignmentId)
                ->where('id', '!=', $this->submission->id)
                ->get();

            $maxSimilarity = 0;
            $matchedWith = [];

            foreach ($previousSubmissions as $prev) {
                $prevContent = $this->extractAllTextFromSubmission($prev);

                if ($prevContent && trim($prevContent) !== '') {
                    $similarity = $this->cosineSimilarity($fileContent, $prevContent);

                    if ($similarity > 0) {
                        $maxSimilarity = max($maxSimilarity, $similarity);
                        $studentName = $prev->student?->user?->name 
                            ?? ($prev->student?->student_id ? 'Student (' . $prev->student->student_id . ')' : 'Another Student');

                        $matchedWith[] = [
                            'submission_id' => $prev->id,
                            'student_name' => $studentName,
                            'similarity' => round($similarity, 2),
                            'submitted_at' => $prev->created_at ? $prev->created_at->toIso8601String() : null,
                        ];
                    }
                }
            }

            usort($matchedWith, fn($a, $b) => $b['similarity'] <=> $a['similarity']);

            $check->update([
                'similarity_percentage' => round($maxSimilarity, 2),
                'matched_with' => $matchedWith,
                'status' => 'completed',
                'notes' => count($matchedWith) > 0 ? null : 'No matching text found across previous submissions.',
            ]);
            $this->detectAiContent($check, $fileContent);

        } catch (\Exception $e) {
            $check->update([
                'status' => 'failed',
                'notes' => 'Plagiarism check error: ' . $e->getMessage(),
            ]);
            \Log::error('Plagiarism Check Error: ' . $e->getMessage());
        }
    }
    private function detectAiContent(check_plagiarism $check, $text)
    {
        try {
            $aiService = new AiDetectionService();
            $result = $aiService->detect($text);

            AiDetection::create([
                'plagiarism_check_id' => $check->id,
                'ai_probability' => $result['ai_probability'],
                'human_probability' => $result['human_probability'],
                'detected_models' => $result['detected_models'],
                'detection_method' => $result['detection_method'],
                'confidence_score' => $result['confidence_score'],
                'details' => $result['details'],
            ]);

            // Update plagiarism check with AI status
            $check->update([
                'ai_status' => 'completed',
                'ai_probability' => $result['ai_probability'],
                'ai_detector' => $result['detection_method'],
                'ai_details' => $result['details'],
            ]);

        } catch (\Exception $e) {
            \Log::error('AI detection failed: ' . $e->getMessage());
            $check->update(['ai_status' => 'failed']);
        }
    }

    private function extractAllTextFromSubmission(AssignmentSubmission $sub): string
    {
        $filePathData = $sub->file_path;
        if (empty($filePathData)) {
            return '';
        }

        $files = [];
        if (is_array($filePathData)) {
            if (isset($filePathData['files']) && is_array($filePathData['files'])) {
                $files = $filePathData['files'];
            } elseif (isset($filePathData['file']) && is_array($filePathData['file'])) {
                $files = [$filePathData['file']];
            } elseif (isset($filePathData[0])) {
                $files = $filePathData;
            } elseif (isset($filePathData['path'])) {
                $files = [$filePathData];
            }
        } elseif (is_string($filePathData)) {
            $files = [['path' => $filePathData]];
        }

        $combinedText = '';
        foreach ($files as $fileItem) {
            $relPath = is_array($fileItem) ? ($fileItem['path'] ?? $fileItem['file_path'] ?? null) : $fileItem;
            if (!$relPath) {
                continue;
            }

            $extracted = $this->extractSingleFileText($relPath);
            if ($extracted) {
                $combinedText .= ' ' . $extracted;
            }
        }

        return trim($combinedText);
    }

    private function extractSingleFileText(string $relPath): ?string
    {
        try {
            $fullPath = Storage::disk('public')->path($relPath);

            if (!file_exists($fullPath) || !is_readable($fullPath)) {
                $fullPath = storage_path('app/public/' . $relPath);
            }

            if (!file_exists($fullPath) || !is_readable($fullPath)) {
                return null;
            }

            $extension = strtolower(pathinfo($fullPath, PATHINFO_EXTENSION));

            switch ($extension) {
                case 'txt':
                    return file_get_contents($fullPath) ?: null;

                case 'pdf':
                    return $this->extractPdfText($fullPath);

                case 'docx':
                case 'doc':
                    return $this->extractDocxText($fullPath);

                default:
                    return null;
            }
        } catch (\Exception $e) {
            \Log::error('Error extracting text from file ' . $relPath . ': ' . $e->getMessage());
            return null;
        }
    }

    private function extractPdfText(string $path): ?string
    {
        try {
            $parser = new \Smalot\PdfParser\Parser();
            $pdf = $parser->parseFile($path);
            return $pdf->getText();
        } catch (\Exception $e) {
            \Log::error('Error extracting text from PDF: ' . $e->getMessage());
            return null;
        }
    }

    private function extractDocxText(string $path): ?string
    {
        try {
            $phpword = \PhpOffice\PhpWord\IOFactory::load($path);
            $text = '';
            foreach ($phpword->getSections() as $section) {
                foreach ($section->getElements() as $element) {
                    if ($element instanceof \PhpOffice\PhpWord\Element\TextRun) {
                        foreach ($element->getElements() as $textElement) {
                            if (method_exists($textElement, 'getText')) {
                                $text .= $textElement->getText() . ' ';
                            }
                        }
                    } elseif (method_exists($element, 'getText')) {
                        $text .= $element->getText() . ' ';
                    }
                }
            }
            return trim($text);
        } catch (\Exception $e) {
            \Log::error('Error extracting text from DOCX: ' . $e->getMessage());
            return null;
        }
    }

    private function normalizeText(string $text): string
    {
        $text = strtolower($text);
        $text = preg_replace('/[^\w\s]/', '', $text);
        $text = preg_replace('/\s+/', ' ', $text);
        return trim($text);
    }

    private function cosineSimilarity(string $text1, string $text2): float
    {
        $text1 = $this->normalizeText($text1);
        $text2 = $this->normalizeText($text2);

        if (empty($text1) || empty($text2)) {
            return 0.0;
        }

        $words1 = array_count_values(str_word_count($text1, 1));
        $words2 = array_count_values(str_word_count($text2, 1));

        $allWords = array_unique(array_merge(array_keys($words1), array_keys($words2)));
        $vec1 = [];
        $vec2 = [];

        foreach ($allWords as $word) {
            $vec1[] = $words1[$word] ?? 0;
            $vec2[] = $words2[$word] ?? 0;
        }

        $dotProduct = array_sum(array_map(fn($a, $b) => $a * $b, $vec1, $vec2));
        $mag1 = sqrt(array_sum(array_map(fn($a) => $a * $a, $vec1)));
        $mag2 = sqrt(array_sum(array_map(fn($a) => $a * $a, $vec2)));

        return ($mag1 == 0 || $mag2 == 0) ? 0.0 : round(($dotProduct / ($mag1 * $mag2)) * 100, 2);
    }
}


