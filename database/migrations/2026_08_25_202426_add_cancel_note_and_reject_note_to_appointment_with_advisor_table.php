
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
        Schema::table('appointment_with_advisors', function (Blueprint $table) {
            $table->text('cancel_note')->nullable()->after('status');
            $table->text('reject_note')->nullable()->after('cancel_note');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('appointment_with_advisors', function (Blueprint $table) {
            $table->dropColumn(['cancel_note', 'reject_note']);
        });
    }
};
