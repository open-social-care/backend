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
        Schema::create('multiple_choice_answers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('form_answer_id')->constrained('form_answers');
            $table->json('answer');
            $table->foreignId('multiple_choice_question_id')->constrained('multiple_choice_questions');
            $table->foreignId('subject_id')->constrained('subjects');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('multiple_choice_answers');
    }
};
