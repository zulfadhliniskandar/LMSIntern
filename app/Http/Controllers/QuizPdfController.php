<?php

namespace App\Http\Controllers;

use App\Models\CourseQuizzes;
use App\Models\CouseQuizAttempts;
use App\Models\Subjects;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class QuizPdfController extends Controller
{
    public function download(Subjects $subject, CourseQuizzes $quiz)
    {
        // Authorization check
        if (!auth()->user()?->isInstructor()) {
            abort(403);
        }

        // Load attempts with relationships
        $attempts = CouseQuizAttempts::with(['user.student'])
            ->where('course_quiz_id', $quiz->id)
            ->whereNotNull('completed_at')
            ->get();

        // Load PDF from blade template
        $pdf = Pdf::loadView('pdf.quiz-results', [
            'subject' => $subject,
            'quiz' => $quiz,
            'attempts' => $attempts,
        ])->setPaper('a4', 'portrait');

        // Download the PDF file
        $fileName = 'Quiz_Results_' . $subject->code . '_' . \Str::slug($quiz->title) . '.pdf';
        return $pdf->download($fileName);
    }
}
