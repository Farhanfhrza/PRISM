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
        Schema::create('activity_log', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->enum('activity_type', ['login', 'logout', 'create', 'update', 'add', 'delete', 'approve', 'reject'])->default('login');
            $table->enum('activity_category', ['authentication', 'request', 'stationery'])->default('authentication');
            $table->text('description');
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
