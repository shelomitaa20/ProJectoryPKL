<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReportsTable extends Migration
{
    public function up()
    {
        Schema::create('reports', function (Blueprint $table) {
            $table->id('report_id'); 
            $table->unsignedBigInteger('admin_id'); 
            $table->string('month', 10);
            $table->string('year', 4);
            $table->integer('total_projects')->default(0);
            $table->integer('total_in_progress')->default(0);
            $table->integer('total_completed')->default(0);
            $table->integer('total_users')->default(0);
            $table->timestamps();

            // Foreign key constraint
            $table->foreign('admin_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('reports');
    }
}
