<?php

namespace App\Services;
use Exception;
use App\Models\LearningMaterials;
use App\Models\Quiz;
use App\Models\QuizQuestion;
use App\Models\QuizOptions;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AIQuizGenerationService
{
    public function generateQuizFromMaterial(LearningMaterials $material, int $numquestions = 5): Quiz
    {
        $text = $material->extracted_text ?: $material->simplified_text;
        if(empty($text)){
            throw new Exception("Material has no extracted text to generate quiz");
        }
        
        $prompt =<<<PROMPT
You are an expert teacher creating an educational quiz.
Based on the following text, generate $numquestions multiple-choice questions.
Rules:
- Each question must have 4 options.
- Exactly one option must be correct.
- Include the correct option letter and explanation for each question.
- Return the output in the following JSON format:
{
"title": "Title of the Quiz based on main topic",
"questions": [
{
"question_text": "Clear multiple-choice question?",
"options": {"A": "Option A", "B": "Option B", "C": "Option C", "D": "Option D"},
"correct_option": "A",
"explanation": "Detailed explanation of why Option A is correct, and why other options are incorrect."
}
]
}

Study Materia:
$text

PROMPT;

    $response = $this->callGeminiApi($prompt);
    $cleanJson = preg_replace('/^```(?:json)?\s*|\s*```$/i', '', trim($response));
    $decodedData = json_decode($cleanJson, true);

    if (empty($decodedData['questions'])) {
        throw new Exception("Failed to generate quiz questions");
    }

    return DB::transaction(function () use ($material, $decodedData) {
        $quiz = Quiz::create([
            'learning_material_id' => $material->id,
            'title' => $decodedData['title'] ?? 'AI Learning Quiz',
            'total_questions' => count($decodedData['questions']),
        ]);

        foreach($decodedData['questions'] as $q){
            $question = QuizQuestion::create([
                'quiz_id' => $quiz->id,
                'question_text' => $q['question_text'],
                'explanation' => $q['explanation'],
            ]);

            foreach($q['options'] as $index=>$optionText){
                QuizOptions::create([
                    'question_id' => $question->id,
                    'option_text' => $optionText,
                    'is_correct' => ($index === ($q['correct_option'] ?? null)),
                ]);
            }
        }

        return $quiz;
    });
    }
    private function callGeminiApi(string $prompt): string
    {
        $geminiApiKey = config('services.gemini.api_key');
        $model = config('services.gemini.model', 'gemini-flash-latest');
        $url = "https://generativelanguage.googleapis.com/v1beta/models/{$model}:generateContent?key={$geminiApiKey}";

        $response = Http::timeout(45)->post($url, 
        [
            "contents" => ["parts" => ["text" => $prompt]],
            "generationConfig" => [
                "responseMimeType" => "application/json",
            ],
        ]);
        if ($response->failed()) {
            throw new Exception("Gemini API Error: " . $response->body());
        }

        return $response->json('candidates.0.content.parts.0.text', '');
    }
}