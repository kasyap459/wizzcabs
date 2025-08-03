<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFareModelsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fare_models', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('country_id')->default(0);
            $table->integer('service_type_id')->default(0);
            $table->time('t1_stime');
            $table->time('t2_stime');
            $table->time('t3_stime');
            $table->time('t4_stime');
            $table->time('t1_etime');
            $table->time('t2_etime');
            $table->time('t3_etime');
            $table->time('t4_etime');
            $table->float('t1_base', 10, 2)->default(0);
            $table->float('t2_base', 10, 2)->default(0);
            $table->float('t3_base', 10, 2)->default(0);
            $table->float('t4_base', 10, 2)->default(0);
            $table->integer('t1_base_dist')->default(0);
            $table->integer('t2_base_dist')->default(0);
            $table->integer('t3_base_dist')->default(0);
            $table->integer('t4_base_dist')->default(0);
            $table->float('t1_distance', 10, 2)->default(0);
            $table->float('t2_distance', 10, 2)->default(0);
            $table->float('t3_distance', 10, 2)->default(0);
            $table->float('t4_distance', 10, 2)->default(0);
            $table->float('t1_minute', 10, 2)->default(0);
            $table->float('t2_minute', 10, 2)->default(0);
            $table->float('t3_minute', 10, 2)->default(0);
            $table->float('t4_minute', 10, 2)->default(0);
            $table->float('t1_waiting', 10, 2)->default(0);
            $table->float('t2_waiting', 10, 2)->default(0);
            $table->float('t3_waiting', 10, 2)->default(0);
            $table->float('t4_waiting', 10, 2)->default(0);
            $table->float('t1_cancel', 10, 2)->default(0);
            $table->float('t2_cancel', 10, 2)->default(0);
            $table->float('t3_cancel', 10, 2)->default(0);
            $table->float('t4_cancel', 10, 2)->default(0);
            $table->time('t1_s_stime');
            $table->time('t2_s_stime');
            $table->time('t3_s_stime');
            $table->time('t4_s_stime');
            $table->time('t1_s_etime');
            $table->time('t2_s_etime');
            $table->time('t3_s_etime');
            $table->time('t4_s_etime');
            $table->float('t1_s_base', 10, 2)->default(0);
            $table->float('t2_s_base', 10, 2)->default(0);
            $table->float('t3_s_base', 10, 2)->default(0);
            $table->float('t4_s_base', 10, 2)->default(0);
            $table->integer('t1_s_base_dist')->default(0);
            $table->integer('t2_s_base_dist')->default(0);
            $table->integer('t3_s_base_dist')->default(0);
            $table->integer('t4_s_base_dist')->default(0);
            $table->float('t1_s_distance', 10, 2)->default(0);
            $table->float('t2_s_distance', 10, 2)->default(0);
            $table->float('t3_s_distance', 10, 2)->default(0);
            $table->float('t4_s_distance', 10, 2)->default(0);
            $table->float('t1_s_minute', 10, 2)->default(0);
            $table->float('t2_s_minute', 10, 2)->default(0);
            $table->float('t3_s_minute', 10, 2)->default(0);
            $table->float('t4_s_minute', 10, 2)->default(0);
            $table->float('t1_s_waiting', 10, 2)->default(0);
            $table->float('t2_s_waiting', 10, 2)->default(0);
            $table->float('t3_s_waiting', 10, 2)->default(0);
            $table->float('t4_s_waiting', 10, 2)->default(0);
            $table->float('t1_s_cancel', 10, 2)->default(0);
            $table->float('t2_s_cancel', 10, 2)->default(0);
            $table->float('t3_s_cancel', 10, 2)->default(0);
            $table->float('t4_s_cancel', 10, 2)->default(0);
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
        Schema::dropIfExists('fare_models');
    }
}
