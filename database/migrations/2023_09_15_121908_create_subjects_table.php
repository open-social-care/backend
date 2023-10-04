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
        Schema::create('subjects', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('relative_name');
            $table->string('relative_relation');
            $table->date('birth_date');
            $table->string('contact_phone');
            $table->string('cpf');
            $table->string('rg');
            $table->enum('skin_color', \App\Enums\SkinColors::getConstantsValues());
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subjects');
    }
};
