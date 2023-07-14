<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ModifyEmployeeProjectIdToForeignKeyInTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('documents_emp_projects', function (Blueprint $table) {
            $table->unsignedBigInteger('employee_project_id')->change();
            $table->foreign('employee_project_id')->references('id')->on('employee_projects')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Schema::table('documents_emp_projects', function (Blueprint $table) {
        //     $table->dropForeign(['employee_project_id']);
        //     $table->integer('employee_project_id')->change();
        // });
    }
}
