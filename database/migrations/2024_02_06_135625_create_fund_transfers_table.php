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
        Schema::create('fund_transfers', function (Blueprint $table) {
            $table->id();
            $table->string('details')->nullable();
            $table->foreignId('account_from')->references('id')->on('accounts')->cascadeOnDelete();
            $table->foreignId('account_to')->references('id')->on('accounts')->cascadeOnDelete();
            $table->string('amount');
            $table->bigInteger('date');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fund_transfers');
    }
};
