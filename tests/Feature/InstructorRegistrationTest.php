<?php

use App\Models\User;
use App\Models\Departments;
use App\Models\InstructorRegistration as InstructorRegistrationModel;
use App\Livewire\InstructorRegistration;
use Livewire\Livewire;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

beforeEach(function () {
    Storage::fake('public');
});

test('guests are redirected to the login page from instructor registration', function () {
    $response = $this->get(route('instructor-registration'));
    $response->assertRedirect(route('login'));
});

test('authenticated users can access instructor registration page', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    $response = $this->get(route('instructor-registration'));
    $response->assertOk();
    $response->assertSeeLivewire(InstructorRegistration::class);
});

test('instructor registration forms can be submitted successfully', function () {
    $user = User::factory()->create(['email' => 'test@example.com']);
    $this->actingAs($user);

    $department = Departments::create([
        'name' => 'Computer Science',
        'code' => 'CS101',
    ]);

    $file = UploadedFile::fake()->create('cv.pdf', 100);

    Livewire::test(InstructorRegistration::class)
        ->set('full_name', 'Dr. John Doe')
        ->set('email', 'test@example.com')
        ->set('phone', '0123456789')
        ->set('address', '123 Tech Street, City')
        ->set('ic_passport', '990101-14-1234')
        ->set('age', 35)
        ->set('gender', 'Male')
        ->set('department_id', $department->id)
        ->set('document_path', $file)
        ->call('submit')
        ->assertHasNoErrors()
        ->assertStatus(200);

    $this->assertDatabaseHas('instructor_registrations', [
        'user_id' => $user->id,
        'full_name' => 'Dr. John Doe',
        'email' => 'test@example.com',
        'phone' => '0123456789',
        'address' => '123 Tech Street, City',
        'ic_passport' => '990101-14-1234',
        'age' => 35,
        'gender' => 'Male',
        'department_id' => $department->id,
    ]);

    // Check if file was stored
    $registration = InstructorRegistrationModel::first();
    Storage::disk('public')->assertExists($registration->document_path);
});
