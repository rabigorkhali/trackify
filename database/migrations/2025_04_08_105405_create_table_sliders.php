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
        Schema::create('sliders', function (Blueprint $table) {
            $table->id();
            $table->text('title')->nullable();
            $table->text('sub_title')->nullable();
            $table->text('thumbnail_image')->nullable();
            $table->float('timer')->nullable();
            $table->text('button1_label')->nullable();
            $table->text('button1_link')->nullable();
            $table->text('button1_color')->nullable();
            $table->text('button1_icon')->nullable();
            $table->text('button2_label')->nullable();
            $table->text('button2_link')->nullable();
            $table->text('button2_color')->nullable();
            $table->text('button2_icon')->nullable();
            $table->text('long_description')->nullable(); //textarea
            $table->text('short_description')->nullable(); //textarea
            $table->integer('position')->nullable(); //textarea
            $table->boolean('status')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sliders');
    }
};
