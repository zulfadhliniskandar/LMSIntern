<?php

namespace App\Services;

use Exception;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class TextSimplificationService
{
    public function processFullContent(string $text, string $targetReadingLevel = 'high_school'): array
    {
        $apiKey = config('services.gemini.api_key');
        if (!$apiKey) {
            $simplified = $this->simplify($text, $targetReadingLevel);
            return [
                'simplified_text' => $simplified,
                'key_points' => $this->fallbackKeyPoints($text),
                'summary' => "This simplified material summarizes the core concepts for easy comprehension.",
            ];
        }

        $truncatedText = mb_substr($text, 0, 12000);

        $prompt = <<<PROMPT
You are an expert AI educator and learning assistant. Analyze the provided study material and complete the following 3 tasks:

1. SIMPLIFY: Rewrite the content into clear, accessible language suitable for {$targetReadingLevel} students.
   - Strictly format the simplified content into engaging, highly readable Markdown.
   - Use section subheadings (### Topic Title) to break up topics into logical modules.
   - Extensive use of bullet points (- ) and numbered lists (1. ) instead of long walls of text.
   - Highlight key terms, essential vocabulary, and definitions in bold (**keyword**).
   - Use callout blockquotes (> **Key Takeaway:** explanation) for critical takeaways or definitions.
   - Keep sentences short, active, and crystal-clear. Retain all factual accuracy.

2. KEY POINTS: Extract 4 to 6 concise, highly essential key bullet points.
3. EXECUTIVE SUMMARY: Write a clear 2-paragraph overview summarizing the core ideas.

Respond strictly in valid JSON format with the following exact keys:
{
  "simplified_text": "your simplified markdown text here",
  "key_points": ["point 1", "point 2", "point 3", "point 4"],
  "summary": "2 paragraph summary here"
}

Material Content:
{$truncatedText}
PROMPT;

        try {
            $response = $this->callGeminiApi($prompt);
            $cleanJson = preg_replace('/^```(?:json)?\s*|\s*```$/i', '', trim($response));
            $decoded = json_decode($cleanJson, true);

            if (is_array($decoded) && !empty($decoded['simplified_text'])) {
                return [
                    'simplified_text' => trim($decoded['simplified_text']),
                    'key_points' => is_array($decoded['key_points'] ?? null) ? $decoded['key_points'] : $this->fallbackKeyPoints($text),
                    'summary' => trim($decoded['summary'] ?? "This simplified material summarizes the core concepts for easy comprehension."),
                ];
            }
        } catch (Exception $e) {
            Log::warning('Consolidated Gemini process failed, using individual fallbacks: ' . $e->getMessage());
        }

        $simplified = $this->simplify($text, $targetReadingLevel);
        return [
            'simplified_text' => $simplified,
            'key_points' => $this->generateKeyPoints($simplified),
            'summary' => $this->generateSummary($simplified),
        ];
    }

    public function simplify(string $text, string $targetReadingLevel = 'high_school'): string
    {
        $apiKey = config('services.gemini.api_key');
        if (!$apiKey) {
            throw new Exception('Gemini API key is not configured in environment.');
        }

        $chunks = $this->splitTextIntoChunks($text, 3000);
        $simplifiedChunks = [];

        foreach ($chunks as $chunk) {
            $simplifiedChunk = $this->callGeminiApi(
                $this->getSimplificationPrompt($chunk, $targetReadingLevel)
            );
            if (!empty($simplifiedChunk)) {
                $simplifiedChunks[] = trim($simplifiedChunk);
            }
        }

        $result = implode("\n\n", array_filter($simplifiedChunks));
        return $result ?: $text;
    }

    public function generateKeyPoints(string $text): array
    {
        $apiKey = config('services.gemini.api_key');
        if (!$apiKey) {
            return $this->fallbackKeyPoints($text);
        }

        $prompt = <<<PROMPT
Extract 4 to 6 essential key points from the following learning material.
Provide each key point on a new line starting with a dash (-).
Keep each point concise, clear, and direct.

Text:
{$text}
PROMPT;

        try {
            $response = $this->callGeminiApi($prompt);
            $lines = explode("\n", $response);
            $points = [];
            foreach ($lines as $line) {
                $trimmed = trim(preg_replace('/^[\s\-*•\d\.]+/u', '', $line));
                if (!empty($trimmed)) {
                    $points[] = $trimmed;
                }
            }
            return array_slice($points, 0, 6) ?: $this->fallbackKeyPoints($text);
        } catch (Exception $e) {
            Log::warning('Key points generation fallback triggered: ' . $e->getMessage());
            return $this->fallbackKeyPoints($text);
        }
    }

    public function generateSummary(string $text): string
    {
        $prompt = <<<PROMPT
Provide a clear, well-structured information overview and summary of the following material:

{$text}
PROMPT;

        try {
            $response = $this->callGeminiApi($prompt);
            return trim($response) ?: "This simplified material summarizes the core concepts for easy comprehension.";
        } catch (Exception $e) {
            Log::warning('Summary generation fallback triggered: ' . $e->getMessage());
            return "This simplified version presents the key concepts and main ideas for quick review.";
        }
    }

    public function assessReadability(string $text): array
    {
        $words = max(str_word_count(strip_tags($text)), 1);
        $sentences = max(preg_match_all('/[.!?]+/', $text), 1);
        $syllables = max($this->countSyllablesInText($text), 1);

        // Flesch Reading Ease Formula
        $score = round(206.835 - (1.015 * ($words / $sentences)) - (84.6 * ($syllables / $words)), 1);
        $score = max(0, min(100, $score));

        if ($score >= 80) {
            $level = 'Elementary School';
        } elseif ($score >= 65) {
            $level = 'Middle School';
        } elseif ($score >= 50) {
            $level = 'High School';
        } else {
            $level = 'College / Advanced';
        }

        return [
            'score' => $score,
            'level' => $level,
        ];
    }

    private function callGeminiApi(string $prompt): string
    {
        $apiKey = config('services.gemini.api_key');
        $models = array_unique([
            config('services.gemini.model', 'gemini-flash-latest'),
            'gemini-flash-latest',
            'gemini-3.5-flash-lite',
            'gemini-flash-lite-latest',
        ]);

        $lastError = '';

        foreach ($models as $model) {
            $url = "https://generativelanguage.googleapis.com/v1beta/models/{$model}:generateContent?key={$apiKey}";

            try {
                $response = Http::withHeaders([
                    'Content-Type' => 'application/json',
                ])->timeout(45)->post($url, [
                    'contents' => [
                        [
                            'parts' => [
                                ['text' => $prompt],
                            ],
                        ],
                    ],
                ]);

                if ($response->successful()) {
                    $data = $response->json();
                    $text = $data['candidates'][0]['content']['parts'][0]['text'] ?? '';
                    if (!empty($text)) {
                        return $text;
                    }
                }

                $lastError = $response->body();
                Log::warning("Gemini API call failed for model {$model}: " . $lastError);
            } catch (Exception $e) {
                $lastError = $e->getMessage();
                Log::warning("Gemini API Exception for model {$model}: " . $lastError);
            }
        }

        throw new Exception('Gemini API request failed for all models: ' . $lastError);
    }

    private function getSimplificationPrompt(string $text, string $targetReadingLevel): string
    {
        $levelGuidance = match ($targetReadingLevel) {
            'elementary' => 'suitable for elementary school students (ages 8-10)',
            'middle_school' => 'suitable for middle school students (ages 11-14)',
            'high_school' => 'suitable for high school students (ages 15-18)',
            'college' => 'suitable for introductory college students',
            default => 'suitable for high school students',
        };

        return <<<PROMPT
Please simplify the following text to make it {$levelGuidance}.

Guidelines for Maximum Readability:
1. Format output using clean Markdown (subheadings ###, bullet points -, numbered lists 1., bold key terms **term**, callouts > Note:).
2. Avoid long, dense blocks of text. Break down key concepts into concise bulleted lists.
3. Use simple, clear, everyday language instead of overly complex vocabulary.
4. Remove unnecessary jargon, or explain essential technical terms simply using bold text (**term**: explanation).
5. Use active voice where possible.
6. Retain all key information, facts, and core concepts.
7. Make sure dont over simplify the information, need to keep important information for the uploaded materials

Original text:
{$text}

Provide ONLY the simplified Markdown text without introductory commentary or meta-text.
PROMPT;
    }

    private function splitTextIntoChunks(string $text, int $chunkSize = 3000): array
    {
        $sentences = preg_split('/(?<=[.!?])\s+/u', $text);
        $chunks = [];
        $currentChunk = [];
        $currentLength = 0;

        foreach ($sentences as $sentence) {
            if ($currentLength + strlen($sentence) > $chunkSize && !empty($currentChunk)) {
                $chunks[] = implode(' ', $currentChunk);
                $currentChunk = [];
                $currentLength = 0;
            }
            $currentChunk[] = $sentence;
            $currentLength += strlen($sentence) + 1;
        }

        if (!empty($currentChunk)) {
            $chunks[] = implode(' ', $currentChunk);
        }

        return empty($chunks) ? [$text] : $chunks;
    }

    private function fallbackKeyPoints(string $text): array
    {
        $sentences = preg_split('/(?<=[.!?])\s+/', strip_tags($text));
        $filtered = array_values(array_filter($sentences, fn($s) => strlen(trim($s)) > 20));
        return array_slice($filtered, 0, 5);
    }

    private function countSyllablesInText(string $text): int
    {
        $words = preg_split('/\s+/', strtolower(preg_replace('/[^a-z\s]/', '', $text)));
        $totalSyllables = 0;

        foreach ($words as $word) {
            if (empty($word)) continue;
            // Simple syllable counter heuristic for English words
            $wordSyllables = preg_match_all('/[aeiouy]{1,2}/i', $word);
            if (str_ends_with($word, 'e') && !str_ends_with($word, 'le') && $wordSyllables > 1) {
                $wordSyllables--;
            }
            $totalSyllables += max(1, $wordSyllables);
        }

        return $totalSyllables;
    }
}
