<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDispatchersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dispatchers', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('admin_id')->nullable();
            $table->string('name');
            $table->string('email', 191)->unique();
            $table->string('dial_code')->nullable();
            $table->string('mobile')->nullable();
            $table->string('password');
            $table->integer('country_id')->nullable();
            $table->integer('partner_id')->default(0);
            $table->integer('status')->default(0);
            $table->string('dispatch_address')->nullable();
            $table->double('dispatch_lat', 15, 8)->nullable();
            $table->double('dispatch_long', 15, 8)->nullable();
            $table->integer('dispatch_zoom')->nullable();
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
        Schema::drop('dispatchers');
    }
}
