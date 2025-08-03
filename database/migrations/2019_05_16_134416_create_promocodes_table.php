<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePromocodesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('promocodes', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('promo_code');
            $table->float('discount', 10, 2)->default(0);
            $table->string('discount_type')->nullable();
            $table->string('user_type')->nullable();
            $table->integer('use_count')->nullable();
            $table->timestamp('starting_at')->nullable();
            $table->timestamp('expiration')->nullable();
            $table->enum('status', ['ADDED', 'USED','EXPIRED']);
            $table->string('description')->nullable();
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
        Schema::dropIfExists('promocodes');
    }
}
