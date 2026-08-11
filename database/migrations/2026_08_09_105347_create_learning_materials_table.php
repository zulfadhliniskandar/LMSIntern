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
        Schema::create('learning_materials', function (Blueprint $table) {
            $table->id();
            $table->string('student_id');
            $table->foreign('student_id')->references('student_id')->on('students')->onDelete('cascade');
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('original_file_path');
            $table->string('original_filename');
            $table->string('file_type');
            $table->integer('file_size');
            $table->longText('extracted_text')->nullable();
            $table->longText('simplified_text')->nullable();
            $table->string('simplified_pdf_path')->nullable();
            $table->enum('simplification_status', ['pending', 'processing', 'completed', 'failed'])->default('pending');
            $table->integer('original_word_count')->nullable();
            $table->integer('simplified_word_count')->nullable();
            $table->decimal('simplification_percentage', 5, 2)->nullable();
            $table->string('original_reading_level')->nullable();
            $table->decimal('original_readability_score', 5, 2)->nullable();
            $table->string('simplified_reading_level')->nullable();
            $table->decimal('simplified_readability_score', 5, 2)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('learning_materials');
    }
};
