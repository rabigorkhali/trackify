<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('project_id');
            $table->string('ticket_key')->unique();
            $table->text('title');
            $table->text('description')->nullable();
            $table->unsignedBigInteger('ticket_status_id');
            $table->string('priority')->default('medium'); // low, medium, high, critical
            $table->string('type')->default('task'); // bug, task, story, epic
            $table->unsignedBigInteger('assignee_id')->nullable();
            $table->unsignedBigInteger('reporter_id')->nullable();
            $table->date('due_date')->nullable();
            $table->integer('story_points')->nullable();
            $table->integer('status')->default(1);
            $table->timestamps();
            $table->foreign('project_id')->references('id')->on('projects');
            $table->foreign('ticket_status_id')->references('id')->on('ticket_statuses');
            $table->foreign('assignee_id')->references('id')->on('users')->onDelete('set null');
            $table->foreign('reporter_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tickets');
    }
};
