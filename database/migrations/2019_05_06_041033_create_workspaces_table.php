<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWorkspacesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('workspaces', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('slug');
            $table->integer('created_by');
            $table->string('lang',5)->default('en');
            $table->string('currency')->default('$');
            $table->string('currency_code')->nullable();
            $table->string('company')->nullable();
            $table->string('address')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('zipcode')->nullable();
            $table->string('country')->nullable();
            $table->string('telephone')->nullable();
            $table->string('logo')->nullable();
            $table->integer('is_stripe_enabled')->default(0);
            $table->text('stripe_key')->nullable();
            $table->text('stripe_secret')->nullable();
            $table->integer('is_paypal_enabled')->default(0);
            $table->text('paypal_mode')->nullable();
            $table->text('paypal_client_id')->nullable();
            $table->text('paypal_secret_key')->nullable();
            $table->string('invoice_template')->nullable();
            $table->string('invoice_color')->nullable();
            $table->text('invoice_footer_title')->nullable();
            $table->text('invoice_footer_notes')->nullable();
            $table->integer('is_active')->default(1);
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
        Schema::dropIfExists('workspaces');
    }
}
