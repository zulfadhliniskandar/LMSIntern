<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\CreateDepartments;
use App\Livewire\ListDeparments;
use App\Livewire\StudentRegistration;
use App\Livewire\InstructorRegistration;
use App\Livewire\Advising;
use App\Livewire\InstructorAdvising;


Route::view('/', 'welcome')->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::view('dashboard', 'dashboard')->name('dashboard');
    Route::get('/departments', ListDeparments::class)->name('listDepartments');
    Route::get('/create-departments', CreateDepartments::class)->name('createDepartments');
    Route::get('/student-registration', StudentRegistration::class)->name('student-registration');
    Route::get('/instructor-registration', InstructorRegistration::class)->name('instructor-registration');
    Route::get('/advising', Advising::class)->name('advising');
    Route::get('instructor-advising', InstructorAdvising::class)->name('instructor-advising');
    Route::impersonate();
});

require __DIR__.'/settings.php';
