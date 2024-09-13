<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTasksTable extends Migration
{
    public function up()
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->id('task_id');
            $table->unsignedBigInteger('project_id'); 
            $table->string('name');
            $table->text('description')->nullable();
            $table->enum('status', ['To Do', 'In Progress', 'Completed'])->default('To Do');
            $table->unsignedBigInteger('assigned_to'); 
            $table->date('due_date')->nullable();
            $table->timestamps();

            // Foreign key constraints
            $table->foreign('project_id')->references('project_id')->on('projects')->onDelete('cascade');
            $table->foreign('assigned_to')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('tasks');
    }
}