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
        Schema::create('grades', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('month'); // e.g. "09" or "2026-09"
            $table->integer('year'); // e.g. 2026
            $table->integer('discipline_score');
            $table->integer('teamwork_score');
            $table->integer('skill_score');
            $table->decimal('average_score', 5, 2);
            $table->text('notes')->nullable();
            $table->timestamps();
            
            // Unique constraint so an intern only gets 1 grade per month per year
            $table->unique(['user_id', 'month', 'year']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('grades');
    }
};
