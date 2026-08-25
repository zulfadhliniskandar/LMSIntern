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
3. EXPRESSIVE TTS CADENCE: Use expressive punctuation (ellipses '...', em-dashes '—', question marks '?', exclamation marks '!') to guide text-to-speech cadence, dramatic pauses, and vocal inflection.
4. NO FORMATTING SYMBOLS IN AUDIO: Do NOT output formatting symbols like asterisks (* or **), markdown headers (#), tildes (~), underscores (_), backticks (`), or bullet symbols. Strip all formatting characters so TTS engines do not read out symbol names like 'asterisk' or 'hashtag'. DO KEEP essential functional characters like '@' in email addresses, '%' in percentages, '$' in currency, and standard punctuation marks.
5. Keep all essential educational facts, definitions, and concepts accurate and complete.
6. Output MUST BE strictly a valid JSON array of dialogue turn objects with exact keys "speaker" (value: "host", "expert", or "student") and "text" (spoken dialogue string). No markdown fences or commentary outside the JSON array.
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
2. EXPRESSIVE TTS CADENCE: Use expressive punctuation (ellipses '...', em-dashes '—', question marks '?', exclamation marks '!') to guide text-to-speech cadence, dramatic pauses, and vocal inflection.
3. CLARITY & PROFESSORIAL TONE: Explain concepts clearly as an articulate educator would, ensuring natural verbal transitions between topics.
4. REMOVE ARTIFACTS: Completely strip out raw file headers, dates, course codes, author lines (e.g. "Update by Dr Del"), slide counters, and repetitive slide titles.
5. NO STAGE DIRECTIONS: Do NOT include bracketed sound cues like [Music], [Pause], [Intro], or character labels like "Host:". Provide ONLY plain spoken dialogue text.
6. NO FORMATTING SYMBOLS IN AUDIO: Do NOT output formatting symbols like asterisks (* or **), markdown headers (#), tildes (~), underscores (_), backticks (`), or bullet symbols. Strip all formatting characters so TTS engines do not read out symbol names like 'asterisk' or 'hashtag'. DO KEEP essential functional characters like '@' in email addresses, '%' in percentages, '$' in currency, and standard punctuation marks.
7. LENGTH & RETENTION: Keep all essential educational information, definitions, and concepts intact while making the text pleasant to listen to.
PROMPT;
        }

        try {
            $response = $this->callGeminiApi($prompt, $geminiApiKey, $generationMode === 'podcast');
            $cleanText = trim($response);

            if (empty($cleanText)) {
                return $this->cleanFallbackText($rawText);
            }

            return $this->cleanTextForTts($cleanText);

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

    public function cleanTextForTts(string $text): string
    {
        if (empty($text)) {
            return '';
        }

        // If text is JSON array (e.g. podcast dialogue turns), sanitize each text element
        $trimmed = trim($text);
        if ((str_starts_with($trimmed, '[') && str_ends_with($trimmed, ']')) || (str_starts_with($trimmed, '{') && str_ends_with($trimmed, '}'))) {
            $decoded = json_decode($trimmed, true);
            if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                $sanitized = array_map(function ($item) {
                    if (is_array($item) && isset($item['text'])) {
                        $item['text'] = $this->cleanTextForTts($item['text']);
                    }
                    return $item;
                }, $decoded);
                return json_encode($sanitized, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
            }
        }

        // 1. Strip HTML tags
        $cleaned = strip_tags($text);

        // 2. Markdown links [Label](url) -> Label
        $cleaned = preg_replace('/\[([^\]]+)\]\([^)]+\)/', '$1', $cleaned);

        // 3. Code blocks ```code``` and inline backticks `code`
        $cleaned = preg_replace('/```[\s\S]*?```/', '', $cleaned);
        $cleaned = preg_replace('/`([^`]+)`/', '$1', $cleaned);
        $cleaned = str_replace('`', '', $cleaned);

        // 4. Bold / Italic asterisks (**text**, *text*) -> text
        $cleaned = preg_replace('/\*{1,3}([^*]+)\*{1,3}/', '$1', $cleaned);

        // 5. Bold / Italic underscores (__text__, _text_) -> text
        $cleaned = preg_replace('/_{1,3}([^_]+)_{1,3}/', '$1', $cleaned);

        // 6. Strikethrough tildes (~~text~~, ~text~) -> text
        $cleaned = preg_replace('/~{1,2}([^~]+)~{1,2}/', '$1', $cleaned);

        // 7. Remove remaining standalone asterisks, tildes, carets, backslashes, bullet characters
        $cleaned = preg_replace('/[*\x{2022}\x{2023}\x{25E6}\x{2043}\x{204B}~^\\\]/u', '', $cleaned);

        // 8. Markdown headers at line start (### Title -> Title)
        $cleaned = preg_replace('/^\s*#+\s*/m', '', $cleaned);
        // Hashtags / # before words/numbers (#1 -> 1, #topic -> topic)
        $cleaned = preg_replace('/#([a-zA-Z0-9]+)/', '$1', $cleaned);
        $cleaned = str_replace('#', '', $cleaned);

        // 9. Bullet point prefixes at line start or list items (- item, + item, * item)
        $cleaned = preg_replace('/^\s*[\-–—+•*]\s+/m', '', $cleaned);
        // Standalone dashes with spaces around them " - " -> ", " so TTS pauses naturally instead of saying "dash"
        $cleaned = preg_replace('/\s+[\-–—]\s+/', ', ', $cleaned);

        // 10. Table pipe separators | -> comma pause
        $cleaned = str_replace('|', ', ', $cleaned);

        // 11. Normalize commas and excessive whitespace
        $cleaned = preg_replace('/([.!?,])\s*,+/', '$1', $cleaned);
        $cleaned = preg_replace('/,\s*,+/', ',', $cleaned);
        $cleaned = preg_replace('/[ \t]+/', ' ', $cleaned);
        $cleaned = preg_replace('/\n{3,}/', "\n\n", $cleaned);

        return trim($cleaned);
    }

    public function cleanFallbackText(string $text): string
    {
        $cleaned = preg_replace('/(?i)(slide|chapter)\s+\d+/i', '', $text);
        return $this->cleanTextForTts($cleaned);
    }
}
