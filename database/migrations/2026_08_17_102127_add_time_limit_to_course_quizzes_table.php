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
        Schema::table('course_quizzes', function (Blueprint $table) {
            $table->integer('time_limit')->nullable()->comment('Time limit in minutes')->after('close_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('course_quizzes', function (Blueprint $table) {
            $table->dropColumn('time_limit');
        });
    }
};
