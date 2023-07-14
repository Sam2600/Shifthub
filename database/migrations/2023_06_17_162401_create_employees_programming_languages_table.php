<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmployeesProgrammingLanguagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employees_programming_languages', function (Blueprint $table) {
            $table->id();
            $table->integer('employee_id');
            $table->integer('programming_language_id');
            // $table->foreignId('employee_id')->constrained('employees')->onDelete('cascade');
            // $table->foreignId('programming_language_id')->constrained('programming_languages')->onDelete('cascade');
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
        Schema::dropIfExists('employees_programming_languages');
    }
}
