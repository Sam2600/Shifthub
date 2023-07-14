<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDocumentsEmpProjectsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('documents_emp_projects', function (Blueprint $table) {
            $table->id();
            $table->string('filename', 250);
            $table->string('filesize', 250);
            $table->string('filepath', 250);
            $table->integer('employee_project_id');
            // $table->foreignId('employee_project_id')->constrained('employees_projects')->onDelete('cascade');
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
        Schema::dropIfExists('documents_emp_projects');
    }
}
