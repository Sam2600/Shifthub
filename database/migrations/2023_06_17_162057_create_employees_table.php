<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateEmployeesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Added condition if table is already exist, we skip for creating a new one
        if (! Schema::hasTable('employees')) {
            Schema::create('employees', function (Blueprint $table) {
                $table->id();
                $table->string('employee_id', 50);
                $table->string('name', 50);
                $table->string('nrc', 50);
                $table->string('phone', 15);
                $table->string('email', 50);
                $table->string('gender', 10);
                $table->string('dateOfBirth', 50);
                $table->string('address', 255);
                $table->string('programming_languages', 20);
                $table->string('languages', 20);
                $table->string('career', 25);
                $table->string('level', 25);
                $table->string('photo', 255);
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
        // This prevents from foreign key constraints deletions
        DB::statement('SET FOREIGN_KEY_CHECKS = 0');
        Schema::dropIfExists('employees');
        DB::statement('SET FOREIGN_KEY_CHECKS = 1');
    }
}
