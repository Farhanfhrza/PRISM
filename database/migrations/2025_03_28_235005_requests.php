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
        Schema::create('requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained('employees');
            $table->timestamp('submit')->useCurrent();
            $table->timestamp('approved')->nullable();
            $table->enum('status', ['pending', 'accepted', 'rejected'])->default('pending'); 
            $table->text('information')->nullable();
            $table->timestamps();

            // Add index for better performance  
            $table->index('employee_id');  
            $table->index('status');
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('requests');
    }
};
