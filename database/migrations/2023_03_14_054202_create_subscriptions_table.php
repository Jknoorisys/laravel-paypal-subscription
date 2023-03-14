<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('subscriptions', function (Blueprint $table) {
            $table->id();
            $table->string('user_id');
            $table->string('paypal_order_id');
            $table->text('paypal_url');
            $table->string('plan_id');
            $table->string('plan_name');
            $table->string('currency');
            $table->string('price');
            $table->string('subscription_id');
            $table->string('start_date');
            $table->string('end_date');
            $table->string('invoice_url');
            $table->string('status');
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
        Schema::dropIfExists('subscriptions');
    }
};
