<?php

namespace App\Services;

use Exception;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AudioScriptGeneratorService
{
    /**
     * Send raw document or extracted text to Gemini AI to generate a polished audio script.
     * Supports both Solo Narration and 3-Person Podcast Discussion modes in both English & Bahasa Melayu.
     */
    public function generateScript(string $rawText, string $style = 'default', string $language = 'en-US', string $generationMode = 'solo'): string
    {
        $geminiApiKey = config('services.gemini.api_key');
        if (!$geminiApiKey) {
            return $this->cleanFallbackText($rawText);
        }

        $truncatedText = mb_substr($rawText, 0, 15000);

        // Language Directive
        $languageDirective = ($language === 'ms-MY')
            ? 'LANGUAGE DIRECTIVE: Write the script strictly in fluent, natural, proper Bahasa Melayu.'
            : 'LANGUAGE DIRECTIVE: Write the script strictly in clear, natural English.';

        if ($generationMode === 'podcast') {
            $prompt = <<<PROMPT
You are an expert educational podcast scriptwriter.
Convert the following raw study material into an engaging, interactive 3-person educational podcast script.

ROLES:
- "host" (Alex): The enthusiastic podcast host who introduces the topic, asks engaging questions, and guides the episode.
- "expert" (Dr. Sarah): The knowledgeable professor/expert who explains core concepts clearly and thoroughly.
- "student" (Jordan): A curious student who asks real-world clarifying questions and summarizes key takeaways.

{$languageDirective}

RULES:
1. Create a natural, conversational 3-way dialogue exploring all key topics in the study material.
2. Strip out raw slide titles (like "Chapter 1", "CMPF 124"), bullet symbols (•, \u2022), page numbers, and raw formatting.
3. Keep all essential educational facts, definitions, and concepts accurate and complete.
4. Output MUST BE strictly a valid JSON array of dialogue turn objects with exact keys "speaker" (value: "host", "expert", or "student") and "text" (spoken dialogue string). No markdown fences or commentary outside the JSON array.

Example JSON output format:
[
  {"speaker": "host", "text": "Welcome everyone to today's study podcast!"},
  {"speaker": "expert", "text": "Hi Alex! Excited to dive into operating systems today."},
  {"speaker": "student", "text": "Hey guys, I had a question about how multitasking works."}
]

Raw Content to Transform:
{$truncatedText}
PROMPT;
        } else {
            // Solo narration mode
            $styleInstructions = match ($style) {
                'newscast' => 'ADAPTIVE TONE: Write in a professional, authoritative, news anchor presentation style. Use formal, crisp, punchy transitions.',
                'instruction' => 'ADAPTIVE TONE: Write in a step-by-step, clear, direct instructional style. Focus on explaining procedural steps clearly and logically.',
                'cheering' => 'ADAPTIVE TONE: Write in an upbeat, enthusiastic, high-energy, motivational style. Use encouraging language that inspires learning.',
                'customer-service' => 'ADAPTIVE TONE: Write in a warm, friendly, empathetic, and approachable Q&A presentation style.',
                default => 'ADAPTIVE TONE: Write in a clear, articulate, engaging educational podcast narrator style with natural verbal transitions.',
            };

            $prompt = <<<PROMPT
You are an expert AI educational narrator and scriptwriter.
Your task is to take raw study notes, lecture slides, or document text and transform it into a polished, natural, highly engaging audio script designed specifically for single-narrator text-to-speech.

{$languageDirective}

SPEAKING STYLE DIRECTIVE:
{$styleInstructions}

RULES & GOALS:
1. NARRATIVE FLOW: Rewrite bullet points, fragmented lists, slide headers (like "Chapter 1", "CMPF 124", "Slide 3"), unicode bullet symbols (•, \u2022), page numbers, and raw formatting into smooth, flowing, conversational spoken text.
2. CLARITY & PROFESSORIAL TONE: Explain concepts clearly as an articulate educator would, ensuring natural verbal transitions between topics.
3. REMOVE ARTIFACTS: Completely strip out raw file headers, dates, course codes, author lines (e.g. "Update by Dr Del"), slide counters, and repetitive slide titles.
4. NO STAGE DIRECTIONS: Do NOT include bracketed sound cues like [Music], [Pause], [Intro], or character labels like "Host:". Provide ONLY plain spoken dialogue text.
5. LENGTH & RETENTION: Keep all essential educational information, definitions, and concepts intact while making the text pleasant to listen to.

Raw Content to Transform:
{$truncatedText}
PROMPT;
        }

        try {
            $response = $this->callGeminiApi($prompt, $geminiApiKey, $generationMode === 'podcast');
            $cleanText = trim($response);

            if (empty($cleanText)) {
                return $this->cleanFallbackText($rawText);
            }

            return $cleanText;

        } catch (Exception $e) {
            Log::warning('Gemini AI Audio Script Generation fallback: ' . $e->getMessage());
            return $this->cleanFallbackText($rawText);
        }
    }

    private function callGeminiApi(string $prompt, string $apiKey, bool $isJson = false): string
    {
        $model = config('services.gemini.model', 'gemini-flash-latest');
        $url = "https://generativelanguage.googleapis.com/v1beta/models/{$model}:generateContent?key={$apiKey}";

        $payload = [
            "contents" => [
                "parts" => [
                    ["text" => $prompt]
                ]
            ],
            "generationConfig" => [
                "temperature" => 0.7,
                "maxOutputTokens" => 8192,
            ],
        ];

        if ($isJson) {
            $payload['generationConfig']['responseMimeType'] = 'application/json';
        }

        $response = Http::timeout(60)->post($url, $payload);

        if ($response->failed()) {
            throw new Exception("Gemini API Error: " . $response->body());
        }

        return $response->json('candidates.0.content.parts.0.text', '');
    }

    public function cleanFallbackText(string $text): string
    {
        // Strip unicode bullet symbols, slide numbers, and excessive whitespace
        $cleaned = preg_replace('/[\x{2022}\x{2023}\x{25E6}\x{2043}\x{204B}]/u', '', $text);
        $cleaned = preg_replace('/(?i)(slide|chapter)\s+\d+/i', '', $cleaned);
        $cleaned = preg_replace('/\s+/', ' ', $cleaned);
        return trim($cleaned);
    }
}
