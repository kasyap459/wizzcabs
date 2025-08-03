<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePartnersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('partners', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('admin_id')->nullable();
            $table->string('name');
            $table->string('email', 191)->unique();
            $table->string('password');
            $table->string('dial_code')->nullable();
            $table->string('mobile')->nullable();
            $table->integer('country_id')->nullable();
            $table->string('carrier_name')->nullable();
            $table->integer('carrier_percentage')->default(0);
            $table->string('address')->nullable();
            $table->string('logo')->nullable();
            $table->string('pan_no')->nullable();
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
        Schema::drop('partners');
    }
}
