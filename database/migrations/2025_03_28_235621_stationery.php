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
        Schema::create('stationeries', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('category');
            $table->integer('stock');
            $table->text('description');
            $table->foreignId('div_id');
            $table->timestamps();
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
