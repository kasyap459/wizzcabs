<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProviderBankDetailsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('provider_bank_details', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->unsignedInteger('provider_id'); // ✅ Matches providers.id type
            $table->foreign('provider_id')
                ->references('id')
                ->on('providers')
                ->onDelete('cascade');

            // Common bank account info
            $table->string('account_holder_name');
            $table->string('bank_name');
            $table->string('branch_name')->nullable();
            $table->string('bank_address')->nullable();

            // International banking identifiers
            $table->string('account_number');
            $table->string('iban')->nullable();
            $table->string('swift_bic')->nullable();
            $table->string('routing_number')->nullable();

            // Account info
            $table->string('account_type')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('provider_bank_details');
    }
}
