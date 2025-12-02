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
        Schema::create('ticket_labels', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('color')->default('#6c757d');
            $table->text('description')->nullable();
            $table->unsignedBigInteger('project_id')->nullable(); // NULL for global labels
            $table->integer('status')->default(1);
            $table->timestamps();
            
            $table->foreign('project_id')->references('id')->on('projects')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ticket_labels');
    }
};

