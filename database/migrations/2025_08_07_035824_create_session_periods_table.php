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
        Schema::create('session_periods', function (Blueprint $table) {
            $table->id();
            $table->foreignId('play_session_id')->constrained()->onDelete('cascade');
            $table->timestamp('start_time');
            $table->timestamp('end_time')->nullable();
            $table->string('play_type'); // 'time' or 'game'
            $table->foreignId('game_id')->nullable()->constrained()->onDelete('set null');
            $table->unsignedTinyInteger('controller_count');
            $table->decimal('cost', 8, 2)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('session_periods');
    }
};