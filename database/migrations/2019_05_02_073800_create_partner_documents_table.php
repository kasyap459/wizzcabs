<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePartnerDocumentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('partner_documents', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('partner_id');
            $table->string('document_id');
            $table->string('url');
            $table->string('unique_id')->nullable();
            $table->enum('status', ['ASSESSING', 'ACTIVE']);
            $table->timestamp('expires_at')->nullable();
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
        Schema::dropIfExists('partner_documents');
    }
}
