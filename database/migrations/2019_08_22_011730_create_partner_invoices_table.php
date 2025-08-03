<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePartnerInvoicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('partner_invoices', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('partner_id');
            $table->string('invoice_id');
            $table->integer('ride_count')->nullable();
            $table->float('ride_total', 10, 2)->default(0);
            $table->integer('vat_percent')->nullable();
            $table->float('prev_payment', 10, 2)->default(0);
            $table->float('prev_balance', 10, 2)->default(0);
            $table->float('current_payment', 10, 2)->default(0);
            $table->float('total', 10, 2)->default(0);
            $table->float('paid', 10, 2)->default(0);
            $table->float('balance', 10, 2)->default(0);
            $table->string('ride_no')->nullable();
            $table->date('from_date')->nullable();
            $table->date('to_date')->nullable();
            $table->float('cash_total', 10, 2)->default(0);
            $table->float('card_total', 10, 2)->default(0);
            $table->float('commission_total', 10, 2)->default(0);
            $table->float('carrier_total', 10, 2)->default(0);
            $table->float('admin_pay', 10, 2)->default(0);
            $table->float('carrier_pay', 10, 2)->default(0);
            $table->float('commission_percent', 10, 2)->default(0);
            $table->float('commission_vat_percent', 10, 2)->default(0);
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
        Schema::dropIfExists('partner_invoices');
    }
}
