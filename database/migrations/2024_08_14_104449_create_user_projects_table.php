<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserProjectsTable extends Migration
{
    public function up()
    {
        Schema::create('user_projects', function (Blueprint $table) {
            $table->id('user_project_id'); 
            $table->unsignedBigInteger('user_id'); 
            $table->unsignedBigInteger('project_id'); 
            $table->enum('role', ['Owner', 'Collaborator']);
            $table->timestamps();

            // Foreign key constraints
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('project_id')->references('project_id')->on('projects')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('user_projects');
    }
}
