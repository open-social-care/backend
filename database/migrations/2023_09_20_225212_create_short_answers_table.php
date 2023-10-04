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
        Schema::create('short_answers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('short_question_id')->constrained('short_questions');
            $table->foreignId('form_answer_id')->constrained('form_answers');
            $table->foreignId('subject_id')->constrained('subjects');
            $table->string('answer');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('short_answers');
    }
};
