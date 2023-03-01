<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateContractsAttachmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contracts_attachments', function (Blueprint $table) {
            $table->id();
            $table->integer('contract_id');
            $table->integer('user_id')->nullable();
            $table->integer('client_id')->nullable();
            $table->string('files')->nullable();
            $table->integer('workspace_id')->nullable();
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
        Schema::dropIfExists('contracts_attachments');
    }
}
