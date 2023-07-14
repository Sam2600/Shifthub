<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ModifyEmployeeIdAndProjectIdToForeignKeyInTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('employee_projects', function (Blueprint $table) {

            $table->unsignedBigInteger('employee_id')->change();
            $table->foreign('employee_id')->references('id')->on('employees')->onDelete('cascade');

            $table->unsignedBigInteger('project_id')->change();
            $table->foreign('project_id')->references('id')->on('projects')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('employees_projects', function (Blueprint $table) {

            // $table->dropForeign(['employee_id']);
            // $table->integer('employee_id')->change();

            // $table->dropForeign(['project_id']);
            // $table->integer('project_id')->change();

        });
    }
}
