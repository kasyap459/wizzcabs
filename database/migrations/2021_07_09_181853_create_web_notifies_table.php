<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWebNotifiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('web_notifies')) {
            Schema::create('web_notifies', function (Blueprint $table) {
                $table->id();
                $table->string('type');
                $table->string('title');
                $table->integer('status')->default(0);
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasTable('web_notifies')) {
            Schema::dropIfExists('web_notifies');
        }
    }
}
