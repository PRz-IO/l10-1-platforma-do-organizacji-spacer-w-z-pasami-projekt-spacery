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
        Schema::create('accounts', function (Blueprint $table) {
            $table->id();
            $table->string('Name',80);
            $table->string('Last_Name',80);
            $table->string('Login',80)->unique();
            $table->string('Password',80);
            $table->date('Creation_Date')->default(now()->format('Y-m-d'));
            $table->string('Acc_State',80);
            #$table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('accounts');
    }
};
