<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmployeesProjectsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employee_projects', function (Blueprint $table) {
            $table->id();
            $table->integer('employee_id');
            $table->integer('project_id');
            // $table->foreignId('employee_id')->constrained('employees')->onDelete('cascade');
            // $table->foreignId('project_id')->constrained('projects')->onDelete('cascade');
            $table->date('start_date');
            $table->date('end_date');
            $table->softDeletes();
            $table->integer('created_by');
            $table->integer('updated_by')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('employees_projects');
    }
}
