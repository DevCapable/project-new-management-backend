<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateContractsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contracts', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('client_id');
            $table->string('subject')->nullable();
            $table->string('project_id')->nullable();
            $table->integer('value')->nullable();
            $table->integer('type');
            $table->date('start_date');
            $table->date('end_date');
            $table->string('status')->default('off');
            $table->string('description')->nullable();
            $table->longtext('contract_description')->nullable();
            $table->longtext('client_signature')->nullable();
            $table->longtext('company_signature')->nullable();
            $table->integer('workspace_id');
            $table->integer('created_by')->nullable();
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
        Schema::dropIfExists('contracts');
    }
}
