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
        Schema::create('stock_opname', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('initiated_by');
            $table->unsignedBigInteger('approved_by')->nullable();
            $table->date('opname_date');
            $table->enum('opname_status', ['Draft', 'Completed', 'Cancelled', 'Approved'])->default('Draft');
            $table->text('description')->nullable();
            $table->foreignId('div_id')->constrained('divisions');

            $table->timestamps();
        
            $table->foreign('initiated_by')->references('id')->on('users');
            $table->foreign('approved_by')->references('id')->on('users');
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
