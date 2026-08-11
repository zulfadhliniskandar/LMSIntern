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
            // Allow standalone audio generation without a learning material
            $table->unsignedBigInteger('learning_material_id')->nullable()->change();

            // Add error_message column used by GenerateAudio job
            if (!Schema::hasColumn('audio_generations', 'error_message')) {
                $table->text('error_message')->nullable()->after('status');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('audio_generations', function (Blueprint $table) {
            $table->unsignedBigInteger('learning_material_id')->nullable(false)->change();
            $table->dropColumn('error_message');
        });
    }
};
