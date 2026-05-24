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
        Schema::create('schedules', function (Blueprint $table) {
            $table->id();
            $table->Date('Date');
            $table->time('Time');
            $table->foreignId('volunteer_id')->references('id')->on('volunteers')->constrained();
            $table->foreignId('dog_id')->references('id')->on('dogs')->constrained();
            $table->foreignId('supervisor_id')->references('id')->on('workers')->constrained();
            $table->text('Note')->nullable();
            $table->integer('Grade')->nullable();
            // $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('schedules');
    }
};
