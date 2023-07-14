<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ModifyEmployeeIdAndProgrammingLanguageIdToForeignKeyInTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('employees_programming_languages', function (Blueprint $table) {

            $table->unsignedBigInteger('employee_id')->change();
            $table->foreign('employee_id')->references('id')->on('employees');

            $table->unsignedBigInteger('programming_language_id')->change();
            $table->foreign('programming_language_id')->references('id')->on('programming_languages');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('employees_programming_languages', function (Blueprint $table) {

            // $table->dropForeign(['employee_id']);
            // $table->integer('employee_id')->change();

            // $table->dropForeign(['programming_language_id']);
            // $table->integer('programming_language_id')->change();

        });
    }
}
