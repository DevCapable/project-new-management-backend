<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateContractsNotesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contracts_notes', function (Blueprint $table) {
             $table->bigIncrements('id');
            $table->unsignedBigInteger('contract_id');
            $table->string('user_id');
            $table->integer('client_id')->nullable();
            $table->string('notes');
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
        Schema::dropIfExists('contracts_notes');
    }
}
