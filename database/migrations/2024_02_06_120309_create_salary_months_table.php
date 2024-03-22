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
        Schema::create('salary_months', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->unsignedSmallInteger('month');
            $table->unsignedSmallInteger('year');
            $table->enum('status',[0, 1]);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('salary_months');
    }
};
