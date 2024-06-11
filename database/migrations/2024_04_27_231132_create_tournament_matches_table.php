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
        Schema::create('tournament_matches', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tournament_id')->constrained('tournaments');
            $table->integer('round_number');
            $table->foreignId('court_id')->constrained('courts')->nullable();
            $table->foreignId('pair1_id')->constrained('tournament_registrations');
            $table->foreignId('pair2_id')->constrained('tournament_registrations');
            $table->string('set1')->nullable();
            $table->string('set2')->nullable();
            $table->string('set3')->nullable();
            $table->enum('result', ['pending', 'pair1', 'pair2', 'draw'])->default('pending');
            $table->dateTime('scheduled_start_time');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tournament_matches');
    }
};
