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
        Schema::create('fav_dogs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('dog_id')->references('id')->on('dogs')->constrained();
            $table->foreignId('volunteer_id')->references('id')->on('volunteers')->constrained();
            #$table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fav_dogs');
    }
};
