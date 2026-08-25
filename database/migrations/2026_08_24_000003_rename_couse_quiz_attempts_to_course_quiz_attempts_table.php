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
        if (Schema::hasTable('couse_quiz_attempts') && !Schema::hasTable('course_quiz_attempts')) {
            Schema::rename('couse_quiz_attempts', 'course_quiz_attempts');
        } elseif (!Schema::hasTable('course_quiz_attempts')) {
            Schema::create('course_quiz_attempts', function (Blueprint $table) {
                $table->id();
                $table->foreignId('course_quiz_id')->constrained('course_quizzes')->cascadeOnDelete();
                $table->foreignId('user_id')->constrained()->cascadeOnDelete();
                $table->integer('score')->default(0);
                $table->integer('total_questions')->default(0);
                $table->timestamp('started_at')->nullable();
                $table->timestamp('completed_at')->nullable();
                $table->json('answers')->nullable();
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('course_quiz_attempts') && !Schema::hasTable('couse_quiz_attempts')) {
            Schema::rename('course_quiz_attempts', 'couse_quiz_attempts');
        }
    }
};
