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
        Schema::create('check_plagiarisms', function (Blueprint $table) {
            $table->id();
            $table->foreignId('assignment_submissions_id')->constrained()->onDelete('cascade');
            $table->decimal('similarity_percentage', 5, 2)->default(0);
            $table->json('matched_with')->nullable();
            $table->enum('status', ['pending', 'checking', 'completed', 'failed'])->default('pending');
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('check_plagiarisms');
    }
};
