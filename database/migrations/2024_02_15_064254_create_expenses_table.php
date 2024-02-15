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
        Schema::create('expenses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->references('id')->on('users');
            $table->bigInteger('date');
            $table->enum('type',['Incoming','Outgoing']);
            $table->foreignId('expense_type_id')->references('id')->on('expense_types');
            $table->foreignId('account_id')->references('id')->on('accounts');
            $table->enum('status',[0, 1, 2]);
            $table->bigInteger('amount');
            $table->text('details')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('expenses');
    }
};
