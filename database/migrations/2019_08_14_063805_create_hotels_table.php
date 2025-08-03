<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHotelsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hotels', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('admin_id')->nullable();
            $table->string('name');
            $table->string('email')->nullable();
            $table->string('dial_code')->nullable();
            $table->string('mobile')->nullable();
            $table->string('password', 255);
            $table->integer('country_id')->nullable();
            $table->string('picture')->nullable();
            $table->integer('customer_id')->nullable();
            $table->string('address')->nullable();
            $table->double('latitude', 15, 8)->nullable();
            $table->double('longitude',15,8)->nullable();
            $table->decimal('rating', 4, 2)->default(5);
            $table->integer('status')->default(0);
            $table->mediumInteger('otp')->default(0);
            $table->timestamp('email_verified_at')->nullable();
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
        Schema::drop('hotels');
    }
}
