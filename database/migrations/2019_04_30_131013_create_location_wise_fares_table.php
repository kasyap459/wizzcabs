<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLocationWiseFaresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('location_wise_fares', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('source_addr')->nullable();
            $table->integer('destination_addr')->nullable();
            $table->integer('service_type_id')->default(0);
            $table->integer('reverse_loc')->default(0);
            $table->time('t1_stime');
            $table->time('t2_stime');
            $table->time('t3_stime');
            $table->time('t4_stime');
            $table->time('t1_etime');
            $table->time('t2_etime');
            $table->time('t3_etime');
            $table->time('t4_etime');
            $table->float('t1_flat', 10, 2)->default(0);
            $table->float('t2_flat', 10, 2)->default(0);
            $table->float('t3_flat', 10, 2)->default(0);
            $table->float('t4_flat', 10, 2)->default(0);
            $table->time('t1_s_stime');
            $table->time('t2_s_stime');
            $table->time('t3_s_stime');
            $table->time('t4_s_stime');
            $table->time('t1_s_etime');
            $table->time('t2_s_etime');
            $table->time('t3_s_etime');
            $table->time('t4_s_etime');
            $table->float('t1_s_flat', 10, 2)->default(0);
            $table->float('t2_s_flat', 10, 2)->default(0);
            $table->float('t3_s_flat', 10, 2)->default(0);
            $table->float('t4_s_flat', 10, 2)->default(0);
            $table->integer('status')->default(0);
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
        Schema::dropIfExists('location_wise_fares');
    }
}
