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
            // نقوم بتغيير اسم العمود من 'start_time' إلى 'session_start_at'
            $table->renameColumn('start_time', 'session_start_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('play_sessions', function (Blueprint $table) {
            // هذا الكود للتراجع، في حالة أردنا ذلك
            $table->renameColumn('session_start_at', 'start_time');
        });
    }
};