<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ai_detections', function (Blueprint $table) {
            $table->id();
            $table->foreignId('plagiarism_check_id')->constrained('check_plagiarisms')->onDelete('cascade');
            $table->decimal('ai_probability', 5, 2)->default(0);
            $table->decimal('human_probability', 5, 2)->default(100);
            $table->integer('sentences_analyzed')->nullable();
            $table->integer('ai_sentences')->nullable();
            $table->string('detection_method')->nullable();
            $table->decimal('confidence_score', 5, 2)->nullable();
            $table->json('detected_models')->nullable();
            $table->json('details')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ai_detections');
    }
};
