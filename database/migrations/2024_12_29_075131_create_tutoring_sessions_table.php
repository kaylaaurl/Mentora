<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tutoring_sessions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tutor_id')->constrained('users');
            $table->foreignId('tutee_id')->constrained('users');
            $table->string('subject');
            $table->text('description');
            $table->dateTime('scheduled_at');
            $table->integer('duration_minutes');
            $table->decimal('price', 8, 2);
            $table->enum('status', ['pending', 'confirmed', 'completed', 'cancelled'])->default('pending');
            $table->text('notes')->nullable();
            $table->text('feedback')->nullable();
            $table->integer('rating')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tutoring_sessions');
    }
}; 