<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdminsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('admins', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('email', 191)->unique();
            $table->string('password');
            $table->integer('admin_type')->default(0);
            $table->string('picture')->nullable();
            $table->string('admin_address')->nullable();
            $table->double('admin_lat', 15, 8)->nullable();
            $table->double('admin_long', 15, 8)->nullable();
            $table->integer('admin_zoom')->nullable();
            $table->string('time_zone')->nullable();
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
        Schema::drop('admins');
    }
}
