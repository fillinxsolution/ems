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
        Schema::table('user_loans', function (Blueprint $table) {
            $table->string('paid_amount')->nullable();
            $table->string('remaining_amount')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('user_loans', function (Blueprint $table) {
            $table->dropColumn('paid_amount');
            $table->dropColumn('remaining_amount');
        });
    }
};
