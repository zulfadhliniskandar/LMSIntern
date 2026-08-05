<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('appointment_with_advisors', function (Blueprint $table) {
            $table->id();
            $table->string('instructor_id');
            $table->foreign('instructor_id')->references('instructor_id')->on('instructors')->onDelete('cascade');
            $table->string('student_id');
            $table->foreign('student_id')->references('student_id')->on('students')->onDelete('cascade');
            $table->date('date_appointment');
            $table->time('time_appointment')->nullable();
            $table->text('note')->nullable();
            $table->string('status')->default('pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('appointment_with_advisors');
    }
};
