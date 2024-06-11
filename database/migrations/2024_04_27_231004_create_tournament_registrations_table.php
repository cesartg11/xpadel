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
        Schema::create('tournament_registrations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tournament_id')->constrained('tournaments');
            $table->foreignId('player1_id')->constrained('user_profile');
            $table->foreignId('player2_id')->constrained('user_profile');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tournament_registrations');
    }
};
