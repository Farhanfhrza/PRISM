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
        Schema::create('insert_stock', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('stationery_id');
            $table->integer('amount');
            $table->unsignedBigInteger('inserted_by');
            $table->timestamp('inserted_at');
            $table->timestamps();
        
            $table->foreign('stationery_id')->references('id')->on('stationeries');
            $table->foreign('inserted_by')->references('id')->on('users');
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
