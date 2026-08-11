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
        Schema::table('audio_generations', function (Blueprint $table) {
            if (!Schema::hasColumn('audio_generations', 'ai_script')) {
                $table->longText('ai_script')->nullable()->after('text_input');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('audio_generations', function (Blueprint $table) {
            $table->dropColumn('ai_script');
        });
    }
};
