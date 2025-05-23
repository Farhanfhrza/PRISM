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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('stationery_id');
            $table->enum('transaction_type', ['In', 'Out']);
            $table->integer('amount');
            $table->text('description')->nullable();
            $table->string('source_type');
            $table->unsignedBigInteger('source_id');
            $table->timestamp('created_at');
            
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('stationery_id')->references('id')->on('stationeries');
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
