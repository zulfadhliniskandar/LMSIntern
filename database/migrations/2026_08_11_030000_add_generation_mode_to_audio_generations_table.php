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
            if (!Schema::hasColumn('audio_generations', 'generation_mode')) {
                $table->string('generation_mode', 20)->default('solo')->after('pitch');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('audio_generations', function (Blueprint $table) {
            $table->dropColumn('generation_mode');
        });
    }
};
