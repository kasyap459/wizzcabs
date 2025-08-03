<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCorporateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('corporate_users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('corporate_id');
            $table->integer('corporate_group_id');
            $table->string('emp_name');
            $table->string('emp_email');
            $table->string('emp_phone')->nullable();
            $table->string('emp_gender')->nullable();
            $table->string('manager_email')->nullable();
            $table->string('manager_name')->nullable();
            $table->string('emp_code')->nullable();
            $table->string('emp_brand')->nullable();
            $table->string('emp_costcenter')->nullable();
            $table->string('emp_desig')->nullable();
            $table->string('emp_baseloc')->nullable();
            $table->string('custom_field1')->nullable();
            $table->string('custom_field2')->nullable();
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
        Schema::dropIfExists('corporate_users');
    }
}
