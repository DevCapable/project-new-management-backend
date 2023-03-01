<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAppointmentInvoicePaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('appointment_invoice_payments', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('currency');
            $table->string('amount_paid');
            $table->string('txn_id');
            $table->string('payment_type');
            $table->string('payment_status');
            $table->string('payment_by');
            $table->string('user_id');
            $table->integer('chance');

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
        Schema::dropIfExists('appointment_invoice_payments');
    }
}
