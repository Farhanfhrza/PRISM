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
        Schema::create('opname_detail', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('opname_id');
            $table->unsignedBigInteger('stationery_id');
            $table->integer('system_stock');
            $table->integer('actual_stock');
            $table->integer('difference');
            $table->text('note')->nullable();
            $table->timestamps();
        
            $table->foreign('opname_id')->references('id')->on('stock_opname');
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
