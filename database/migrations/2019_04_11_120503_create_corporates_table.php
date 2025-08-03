<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCorporatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('corporates', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('admin_id')->nullable();
            $table->string('legal_name');
            $table->string('display_name')->nullable();
            $table->string('email', 191)->unique();
            $table->string('dial_code')->nullable();
            $table->string('mobile')->nullable();
            $table->integer('country_id')->nullable();
            $table->string('secondary_email')->nullable();
            $table->string('password');
            $table->string('pan_no')->nullable();
            $table->string('picture')->nullable();
            $table->string('address')->nullable();
            $table->integer('notify_customer')->default(0);
            $table->integer('status')->default(0);
            $table->rememberToken();
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
        Schema::drop('corporates');
    }
}
