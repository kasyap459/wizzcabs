<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVehiclesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vehicles', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('admin_id')->nullable();
            $table->string('vehicle_name')->nullable();
            $table->string('vehicle_no')->nullable();
            $table->string('seat')->nullable();
            $table->integer('location_id');
            $table->integer('partner_id')->nullable();
            $table->integer('service_type_id')->default(0);
            $table->string('vehicle_owner')->nullable();
            $table->string('vehicle_model')->nullable();
            $table->string('vehicle_manufacturer')->nullable();
            $table->string('manufacturing_year')->nullable();
            $table->string('vehicle_brand')->nullable();
            $table->string('vehicle_color')->nullable();
            $table->string('insurance_no')->nullable();
            $table->date('insurance_exp')->nullable();
            $table->integer('handicap_access')->default(0);
            $table->integer('travel_pet')->default(0);
            $table->integer('station_wagon')->default(0);
            $table->integer('booster_seat')->default(0);
            $table->integer('child_seat')->default(0);
            $table->integer('booster_count')->nullable();
            $table->string('vehicle_image')->nullable();
            $table->string('preference')->nullable();
            $table->integer('status')->default(0);
            $table->string('custom_field1')->nullable();
            $table->string('custom_field2')->nullable();
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
        Schema::dropIfExists('vehicles');
    }
}
