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
        Schema::table('play_sessions', function (Blueprint $table) {
            // نقوم بحذف الأعمدة التي لم نعد بحاجة إليها
            $table->dropColumn(['expected_end_time', 'duration_in_minutes']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('play_sessions', function (Blueprint $table) {
            // هذا الكود للتراجع، في حالة أردنا ذلك
            $table->timestamp('expected_end_time')->nullable();
            $table->unsignedInteger('duration_in_minutes')->nullable();
        });
    }
};