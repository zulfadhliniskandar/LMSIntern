<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Quiz;
use App\Models\LearningMaterials;
use App\Services\AIQuizGenerationService;
use Exception;


class QuizController extends Controller
{
    public function generate(LearningMaterials $material, AIQuizGenerationService $service)
    {
        if (!$material) {
            return back()->with('error', 'Material not found');
        }
        try {
            $quiz = $service->generateQuizFromMaterial($material);
            return redirect()->route('quizzes.show', $quiz->id)->with('success', 'Quiz generated successfully');
        } catch (Exception $e) {
            \Illuminate\Support\Facades\Log::error('Quiz generation error: ' . $e->getMessage());
            return back()->with('error', 'Failed to generate quiz: ' . $e->getMessage());
        }
    }

    public function show(Quiz $quiz)
    {
        if (!$quiz) {
            return back()->with('error', 'Quiz not found');
        }
        return view('show', compact('quiz'));
    }
}
