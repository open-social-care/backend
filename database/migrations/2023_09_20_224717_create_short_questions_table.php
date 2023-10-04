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
        Schema::create('short_questions', function (Blueprint $table) {
            $table->id();
            $table->string('description');
            $table->string('data_type');
            $table->foreignId('form_template_id')->constrained('form_templates');
            $table->boolean('answer_required')->default(false);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('short_questions');
    }
};
