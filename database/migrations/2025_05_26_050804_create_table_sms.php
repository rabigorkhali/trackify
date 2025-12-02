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
        Schema::create('sms', function (Blueprint $table) {
            $table->id();
            $table->string('title')->default('No Title');
            $table->string('sender')->nullable();
            $table->json('receiver')->nullable();
            $table->text('message');
            $table->string('status')->default('pending');
            $table->json('meta')->nullable(); // Optional metadata like API response, delivery report
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sms');
    }
};
