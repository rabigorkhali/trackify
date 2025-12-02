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
        Schema::create('teams', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable(false);
            $table->string('contact')->nullable(true);
            $table->string('designation')->nullable(true);
            $table->string('facebook_url')->nullable(true);
            $table->string('instagram_url')->nullable(true);
            $table->string('youtube_url')->nullable(true);
            $table->string('linkedin_url')->nullable(true);
            $table->string('twitter_url')->nullable(true);
            $table->date('join_date')->nullable(true);
            $table->text('description')->nullable(true);
            $table->boolean('status')->default(true);
            $table->string('image')->nullable();
            $table->integer('position')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('teams');
    }
};
