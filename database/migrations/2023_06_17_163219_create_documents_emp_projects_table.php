<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
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
        // Added condition if table is already exist, we skip for creating a new one
        if (! Schema::hasTable('documents_emp_projects')) {
            Schema::create('documents_emp_projects', function (Blueprint $table) {
                $table->id();
                $table->foreignId('employees_projects_id')->constrained('employees_projects')->onDelete('cascade');
                $table->string('filename', 250);
                $table->string('filesize', 250);
                $table->string('filepath', 250);
                $table->integer('created_by');
                $table->integer('updated_by')->nullable();
                $table->timestamps();
            });
        }
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
