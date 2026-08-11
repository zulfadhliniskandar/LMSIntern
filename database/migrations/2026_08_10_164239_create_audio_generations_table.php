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
        Schema::create('audio_generations', function (Blueprint $table) {
            $table->id();
            $table->string('student_id');
            $table->foreign('student_id')->references('student_id')->on('students')->onDelete('cascade');
            $table->foreignId('learning_material_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->longText('text_input');
            $table->string('audio_file_path')->nullable();
            $table->integer('audio_duration')->nullable();
            $table->string('file_size')->nullable();
            $table->string('language', 20)->default('en-US');
            $table->string('voice_name');
            $table->string('voice_gender');
            $table->string('style')->default('default');
            $table->decimal('speech_rate', 3, 1)->default(0);
            $table->decimal('pitch')->default(0);
            $table->enum('status', ['pending', 'processing', 'completed', 'failed'])->default('pending');
            $table->integer('plays')->default(0);
            $table->integer('downloads')->default(0);
            $table->timestamp('last_played_at')->nullable();
            $table->timestamps();
        });

        Schema::create('audio_voice_preferences', function(Blueprint $table){
            $table->id();
            $table->string('student_id');
            $table->foreign('student_id')->references('student_id')->on('students')->onDelete('cascade');
            $table->string('preferred_language')->default('en-US');
            $table->string('preferred_voice_name');
            $table->string('preferred_voice_gender');
            $table->string('preferred_style')->default('default');
            $table->decimal('preferred_speech_rate', 3, 1)->default(1.0);
            $table->integer('preferred_pitch')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('audio_generations');
    }
};
