<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_requests', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('admin_id')->nullable();
            $table->integer('booking_id')->nullable();
            $table->string('user_name')->nullable();
            $table->string('user_mobile')->nullable();
            $table->integer('guest')->default(0);
            $table->integer('user_id')->default(0);
            $table->integer('corporate_id')->default(0);
            $table->integer('group_id')->default(0);
            $table->integer('provider_id')->default(0);
            $table->integer('current_provider_id')->default(0);
            $table->integer('partner_id')->default(0);
            $table->integer('service_type_id')->default(0);
            $table->integer('vehicle_id')->default(0);
            $table->integer('hotel_id')->default(0);
            $table->integer('fare_type')->default(0);
            $table->float('estimated_fare', 10, 2)->default(0);
            $table->enum('status', [
                    'SEARCHING',
                    'CANCELLED',
                    'ACCEPTED', 
                    'STARTED',
                    'ARRIVED',
                    'PICKEDUP',
                    'DROPPED',
                    'COMPLETED',
                    'SCHEDULED',
                    'END'
                ]);
            $table->string('push')->nullable();
            $table->enum('cancelled_by', [
                    'NONE',
                    'USER',
                    'PROVIDER',
                    'DISPATCHER',
                    'REJECTED',
                    'NODRIVER'
                ]);
            $table->string('cancel_reason')->nullable();
            $table->integer('cancel_request')->default(0);
            $table->string('cancel_status')->nullable();
            $table->string('booking_by')->nullable();
            $table->enum('payment_mode', [
                    'CASH',
                    'CARD'
                ]);
            $table->boolean('paid')->default(0);
            $table->double('distance', 15, 2);
            $table->integer('minutes')->nullable();
            $table->string('s_address')->nullable();
            $table->double('s_latitude', 15, 8);
            $table->double('s_longitude', 15, 8);
            $table->string('d_address')->nullable();
            $table->double('d_latitude', 15, 8);
            $table->double('d_longitude', 15, 8);
            $table->string('message')->nullable();
            $table->string('comment')->nullable();
            $table->integer('handicap')->default(0);
            $table->integer('pet')->default(0);
            $table->integer('wagon')->default(0);
            $table->integer('booster')->default(0);
            $table->integer('fixed_rate')->default(0);
            $table->integer('child_seat')->default(0);
            $table->integer('passenger_count')->default(0);
            $table->integer('luggage')->default(0);
            $table->integer('ladies_only')->default(0);
            $table->timestamp('assigned_at')->nullable();
            $table->timestamp('schedule_at')->nullable();
            $table->timestamp('accepted_at')->nullable();
            $table->timestamp('started_at')->nullable();
            $table->timestamp('finished_at')->nullable();
            $table->time('waiting_time')->nullable();
            $table->boolean('payment_update')->default(0);
            $table->boolean('user_rated')->default(0);
            $table->boolean('provider_rated')->default(0);
            $table->boolean('use_wallet')->default(0);
            $table->boolean('surge')->default(0);
            $table->longText('route_key');
            $table->softDeletes();
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
        Schema::dropIfExists('user_requests');
    }
}
