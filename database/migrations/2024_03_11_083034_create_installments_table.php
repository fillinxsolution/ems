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
        Schema::create('installments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('salary_month_id')->references('id')->on('salary_months')->cascadeOnDelete();
            $table->foreignId('user_loan_id')->constrained()->cascadeOnDelete();
            $table->text('purpose');
            $table->unsignedInteger('amount');
            $table->date('date');
            $table->enum('status', ['paid', 'pending'])->default('pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('installments');
    }
};
