<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('check_plagiarisms', function (Blueprint $table) {
            if (!Schema::hasColumn('check_plagiarisms', 'ai_status')) {
                $table->enum('ai_status', ['not_checked', 'checking', 'completed', 'failed'])->default('not_checked');
            }
            if (!Schema::hasColumn('check_plagiarisms', 'ai_probability')) {
                $table->decimal('ai_probability', 5, 2)->nullable();
            }
            if (!Schema::hasColumn('check_plagiarisms', 'ai_details')) {
                $table->json('ai_details')->nullable();
            }
            if (!Schema::hasColumn('check_plagiarisms', 'ai_detector')) {
                $table->string('ai_detector')->nullable();
            }
        });
    }

    public function down(): void
    {
        Schema::table('check_plagiarisms', function (Blueprint $table) {
            //
        });
    }
};
