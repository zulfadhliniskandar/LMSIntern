<?php

use App\Livewire\StudentSubjectDetails;
use Illuminate\Support\Facades\Route;
use App\Livewire\CreateDepartments;
use App\Livewire\ListDeparments;
use App\Livewire\StudentRegistration;
use App\Livewire\InstructorRegistration;
use App\Livewire\Advising;
use App\Livewire\InstructorAdvising;
use App\Livewire\ListTakingCourse;
use App\Livewire\InstructorCourse;
use App\Livewire\DetailsAndMaterialSubject;
use App\Livewire\AddMaterialsForm;
use App\Livewire\AdvisorPageForStudent;
use App\Livewire\AdviseeForAdvisorPage;
use App\Livewire\AdviseeProfile;
use App\Livewire\InstructorAdvisingCopy;
use App\Livewire\CreateAssignment;
use App\Livewire\DetailsEachAssignment;
use App\Livewire\ListStudentNotSubmitAssignment;
use App\Livewire\ListStudentSubmitAssignment;
use App\Livewire\DetailsEachSubmission;
use App\Livewire\LearningMaterialUpload;
use App\Livewire\QuizGenerator;
use App\Livewire\AIQuizGeneratorPage;
use App\Http\Controllers\LearningMaterialController;
use App\Http\Controllers\QuizController;
use App\Http\Controllers\AudioController;
use App\Livewire\AudioGenrator;
use App\Livewire\UploadMaterialForAudioGeneration;
use App\Livewire\NotebooklmAudioGenerator;
use App\Livewire\CreateCourseQuiz;
use App\Livewire\TakeCourseQuiz;
use App\Http\Controllers\QuizPdfController;

Route::view('/', 'welcome')->name('home');

use App\Livewire\DetailsEachQuiz;

use App\Livewire\MyStudentForEachSubject;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/instructor-course/{subject}/students', MyStudentForEachSubject::class)->name('instructor-course-students');
    Route::get('/instructor-course/{subject}/course-quiz/{quiz}/pdf', [QuizPdfController::class, 'download'])->name('instructor-course-quiz-pdf');
    Route::view('dashboard', 'dashboard')->name('dashboard');
    //Route::get('/departments', ListDeparments::class)->name('listDepartments');
    //Route::get('/create-departments', CreateDepartments::class)->name('createDepartments'); 
    Route::get('/student-registration', StudentRegistration::class)->name('student-registration'); //normal registration
    Route::get('/instructor-registration', InstructorRegistration::class)->name('instructor-registration'); //normal user
    Route::get('/advising', Advising::class)->name('advising'); //done 403 student
    Route::get('/instructor-advising', InstructorAdvising::class)->name('instructor-advising'); //done 403 instructor
    Route::get('taking-course', ListTakingCourse::class)->name('taking-course');  //student sahaja done 403
    Route::get('/instructor-course', InstructorCourse::class)->name('instructor-course'); //done 403 instructor
    Route::get('/instructor-course/{subject}', DetailsAndMaterialSubject::class)->name('instructor-course-details'); //done403 instructor
    Route::get('/instructor-course/{subject}/add-material', AddMaterialsForm::class)->name('instructor-course-addMaterial'); //done 403 instructor
    Route::get('/taking-course/{subject}', StudentSubjectDetails::class)->name('student-course-details'); //done 403 student
    Route::get('/advisor', AdvisorPageForStudent::class)->name('advisor'); // student
    Route::get('/advisee-for-advisor', AdviseeForAdvisorPage::class)->name('advisee-for-advisor'); //done 403 instructor
    Route::get('/student-profile/{studentId}', AdviseeProfile::class)->name('student-profile'); //done 403 instructor
    Route::get('/instructor-advising-copy', InstructorAdvisingCopy::class)->name('instructor-advising-copy');
    Route::get('instructor-course/{subject}/assignment', CreateAssignment::class)->name('instructor-course-assignment');
    Route::get('/taking-course/{subject}/{assignment}', DetailsEachAssignment::class)->name('course-assignment-details');
    Route::get('instructor-course/{subject}/assignment/{assignment}/student-not-submitted', ListStudentNotSubmitAssignment::class)->name('student-not-submitted');
    Route::get('instructor-course/{subject}/assignment/{assignment}/student-submitted', ListStudentSubmitAssignment::class)->name('student-submitted');
    Route::get('/simplification-service', LearningMaterialUpload::class)->name('simplification-service');
    Route::get('/ai-quiz-generator', AIQuizGeneratorPage::class)->name('ai-quiz-generator');
    Route::post('/simplification-service/upload', [LearningMaterialController::class, 'upload'])->name('simplification-service.upload');
    Route::get('instructor-course/{subject}/assignment/{assignment}/submission/{submission}', DetailsEachSubmission::class)->name('submission-details');
    Route::get('/materials/{material}/quiz-generator', QuizGenerator::class)->name('materials.quiz-generator');
    Route::post('/materials/{material}/generate-quiz', [QuizController::class, 'generate'])->name('materials.generate-quiz');
    Route::get('/quizzes/{quiz}', [QuizController::class, 'show'])->name('quizzes.show');
    Route::get('/audio/{audio}/stream', [AudioController::class, 'stream'])->name('audio.stream');
    Route::get('/audio/{audio}/download', [AudioController::class, 'download'])->name('audio.download');
    Route::get('/notebooklm-audio/{notebooklmGen}/stream', [AudioController::class, 'streamNotebooklm'])->name('notebooklm-audio.stream');
    Route::get('/notebooklm-audio/{notebooklmGen}/download', [AudioController::class, 'downloadNotebooklm'])->name('notebooklm-audio.download');
    
    // Livewire component
    Route::get('/audio-generator', AudioGenrator::class)->name('audio-generator');
    Route::get('/audio-upload', UploadMaterialForAudioGeneration::class)->name('audio-upload');
    Route::get('/notebooklm-audio', NotebooklmAudioGenerator::class)->name('notebooklm-audio');
    Route::get('/instructor-course/{subject}/course-quiz/create', CreateCourseQuiz::class)->name('instructor-course-quiz-create');
    Route::get('/instructor-course/{subject}/course-quiz/{quiz}', DetailsEachQuiz::class)->name('instructor-course-quiz-details');
    Route::get('/taking-course/{subject}/quiz/{courseQuiz}', TakeCourseQuiz::class)->name('student-take-course-quiz');
    Route::impersonate();
});

require __DIR__.'/settings.php';
