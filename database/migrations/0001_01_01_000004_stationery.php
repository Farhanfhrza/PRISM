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
            $table->string('name');
            $table->string('category');
            $table->integer('stock')->default(0);
            $table->string('unit');
            $table->integer('initial_stock')->default(0);
            $table->text('description');
            $table->foreignId('div_id')->constrained('divisions');
            $table->string('barcode')->unique()->nullable()->after('description');
            $table->timestamps();

            $table->unique(['name', 'div_id']);
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
