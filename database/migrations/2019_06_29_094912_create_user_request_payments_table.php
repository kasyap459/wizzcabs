<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserRequestPaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_request_payments', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('request_id');
            $table->string('payment_id')->nullable();
            $table->string('payment_mode')->nullable();
            $table->string('currency')->nullable();
            $table->float('base_fare', 10, 2)->default(0);
            $table->float('distance_fare', 10, 2)->default(0);
            $table->float('min_fare', 10, 2)->default(0);
            $table->float('waiting_fare', 10, 2)->default(0);
            $table->float('vat', 10, 2)->default(0);
            $table->float('discount', 10, 2)->default(0);
            $table->float('toll', 10, 2)->default(0);
            $table->float('extra_fare', 10, 2)->default(0);
            $table->string('extra_desc')->nullable();
            $table->float('cash', 10, 2)->default(0);
            $table->float('total', 10, 2)->default(0);
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
        Schema::dropIfExists('user_request_payments');
    }
}
