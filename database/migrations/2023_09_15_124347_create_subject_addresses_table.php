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
        Schema::create('subject_addresses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('address_id')->constrained('addresses');
            $table->foreignId('subject_id')->constrained('subjects');
            $table->boolean('is_subject_contact')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subject_addresses');
    }
};
