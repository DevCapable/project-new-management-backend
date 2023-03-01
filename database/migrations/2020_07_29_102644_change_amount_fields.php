<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeAmountFields extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table( 'projects', function (Blueprint $table){
            $table->float('budget', 25, 2)->default('0.00')->nullable()->change();
        });

        Schema::table( 'milestones', function (Blueprint $table){
            $table->float('cost', 25, 2)->default('0.00')->nullable()->change();
        });
        
        Schema::table( 'invoice_items', function (Blueprint $table){
            $table->float('price', 25, 2)->default('0.00')->nullable()->change();
        });
        
        Schema::table( 'invoice_payments', function (Blueprint $table){
            $table->float('amount', 25, 2)->default('0.00')->nullable()->change();
        });    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
