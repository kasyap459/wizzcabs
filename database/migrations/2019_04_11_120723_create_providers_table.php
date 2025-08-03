<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProvidersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('providers', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('admin_id')->nullable();
            $table->string('name');
            $table->string('email', 191)->unique();
            $table->string('dial_code')->nullable();
            $table->string('mobile')->nullable();
            $table->string('password', 255)->nullable();
            $table->string('gender')->nullable();
            $table->integer('country_id')->nullable();
            $table->string('avatar')->nullable();
            $table->decimal('rating', 4, 2)->default(5);
            $table->enum('account_status', ['onboarding', 'approved', 'banned']);
            $table->enum('status', ['active', 'offline','riding']);
            $table->integer('partner_id')->nullable();
            $table->integer('service_type_id')->default(0);
            $table->integer('mapping_id')->default(0);
            $table->integer('trip_id')->default(0);
            $table->double('latitude', 15, 8)->nullable();
            $table->double('longitude', 15, 8)->nullable();
            $table->string('address')->nullable();
            $table->string('allowed_service')->nullable();
            $table->string('language')->nullable();
            $table->string('acc_no')->nullable();
            $table->string('license_no')->nullable();
            $table->date('license_expire')->nullable();
            $table->mediumInteger('otp')->default(0);
            $table->string('stripe_cust_id')->nullable();
            $table->float('due_balance')->default(0);
            $table->integer('login_status')->nullable();
            $table->timestamp('active_from')->nullable();
            $table->timestamp('ride_from')->nullable();
            $table->timestamp('login_at')->nullable();
            $table->timestamp('logout_at')->nullable();
            $table->enum('login_by',array('manual','facebook','google'));
            $table->string('social_unique_id')->nullable();
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
        Schema::drop('providers');
    }
}
