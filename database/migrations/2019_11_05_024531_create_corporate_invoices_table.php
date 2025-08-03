<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCorporateInvoicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('corporate_invoices', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('corporate_id');
            $table->string('invoice_id');
            $table->integer('ride_count')->nullable();
            $table->float('ride_total', 10, 2)->default(0);
            $table->float('prev_payment', 10, 2)->default(0);
            $table->float('prev_balance', 10, 2)->default(0);
            $table->float('current_payment', 10, 2)->default(0);
            $table->float('total', 10, 2)->default(0);
            $table->float('paid', 10, 2)->default(0);
            $table->float('balance', 10, 2)->default(0);
            $table->string('ride_no')->nullable();
            $table->date('from_date')->nullable();
            $table->date('to_date')->nullable();
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
        Schema::dropIfExists('corporate_invoices');
    }
}
