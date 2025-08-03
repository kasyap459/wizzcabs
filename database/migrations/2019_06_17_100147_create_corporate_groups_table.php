<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCorporateGroupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('corporate_groups', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('corporate_id');
            $table->string('group_name')->nullable();
            $table->enum('payment_mode', [
                    'AUTOPAY',
                    'REIMBURSED',
                    'SELFPAY'
                ]);
            $table->string('ride_service_type')->nullable();
            $table->string('allowed_days')->nullable();
            $table->string('time_range')->nullable();
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
        Schema::dropIfExists('corporate_groups');
    }
}
