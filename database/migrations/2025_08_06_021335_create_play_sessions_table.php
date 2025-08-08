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
        Schema::create('play_sessions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('device_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->nullable()->comment('Employee who started the session')->constrained()->onDelete('set null');
            
            // --- هذا هو التصميم النهائي للجدول ---
            $table->timestamp('start_time');
            $table->timestamp('expected_end_time')->nullable(); // وقت الانتهاء المتوقع للجلسات المحددة
            $table->timestamp('actual_end_time')->nullable(); // وقت الانتهاء الفعلي
            $table->decimal('total_cost', 8, 2)->nullable();
            $table->string('status')->default('active'); // 'active', 'completed'
            $table->unsignedInteger('duration_in_minutes')->nullable(); // مدة الجلسة المحددة
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('play_sessions');
    }
};