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
        Schema::table('couse_quiz_attempts', function (Blueprint $table) {
            $table->timestamp('started_at')->nullable()->after('total_questions');
            $table->json('answers')->nullable()->after('completed_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('couse_quiz_attempts', function (Blueprint $table) {
            $table->dropColumn(['started_at', 'answers']);
        });
    }
};
