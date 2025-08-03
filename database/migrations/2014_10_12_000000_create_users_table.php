<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('admin_id')->nullable();
            $table->string('name');
            $table->string('email')->nullable();
            $table->string('dial_code')->nullable();
            $table->string('mobile')->nullable();
            $table->string('password', 255)->nullable();
            $table->string('gender')->nullable();
            $table->integer('country_id')->nullable();
            $table->integer('corporate_user_id')->nullable();
            $table->integer('department_id')->nullable();
            $table->integer('report_id')->nullable();
            $table->string('picture')->nullable();
            $table->string('device_token')->nullable();
            $table->string('device_id')->nullable();
            $table->enum('device_type',array('android','ios','web'));
            $table->enum('login_by',array('manual','facebook','google'));
            $table->string('social_unique_id')->nullable();
            $table->double('latitude', 15, 8)->nullable();
            $table->double('longitude',15,8)->nullable();
            $table->integer('trip_id')->default(0);
            $table->string('stripe_cust_id')->nullable();
            $table->float('wallet_balance')->default(0);
            $table->float('due_balance')->default(0);
            $table->integer('due_trip')->nullable();
            $table->decimal('rating', 4, 2)->default(5);
            $table->mediumInteger('otp')->default(0);
            $table->timestamp('email_verified_at')->nullable();
            $table->integer('status')->default(0);
            $table->integer('corporate_status')->default(0);
            $table->string('custom_field1')->nullable();
            $table->string('custom_field2')->nullable();
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
        Schema::dropIfExists('users');
    }
}
