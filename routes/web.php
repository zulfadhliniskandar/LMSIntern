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


Route::view('/', 'welcome')->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
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
    Route::impersonate();
});

require __DIR__.'/settings.php';
