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
        Schema::create('import_csv_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->foreignId('salary_month_id')->references('id')->on('salary_months');
            $table->foreignId('import_csvs_id')->constrained()->cascadeOnDelete();
            $table->string('empleado_id')->nullable();
            $table->string('name')->nullable();
            $table->string('total_days')->nullable();
            $table->string('present_days')->nullable();
            $table->string('late_min')->nullable();
            $table->string('annual_leaves_total')->nullable();
            $table->string('annual_leaves_availed')->nullable();
            $table->string('expected_hrs')->nullable();
            $table->string('expected_min')->nullable();
            $table->string('earned_hrs')->nullable();
            $table->string('earned_min')->nullable();
            $table->string('overtime_hrs')->nullable();
            $table->string('overtime_min')->nullable();
            $table->string('earned_time_in_min')->nullable();
            $table->string('salary_in_min')->nullable();
            $table->string('loan_deduction')->nullable();
            $table->string('fine_deduction')->nullable();
            $table->string('cafe_deduction')->nullable();
            $table->string('wfh')->nullable();
            $table->string('bonus')->nullable();
            $table->string('month_salary')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('import_csv_details');
    }
};
