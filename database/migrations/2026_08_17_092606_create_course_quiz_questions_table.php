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
        Schema::create('course_quiz_questions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_quiz_id')->constrained()->cascadeOnDelete();
            $table->text('question');
            $table->text('explanation')->nullable();
            $table->integer('points')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('course_quiz_questions');
    }
};
