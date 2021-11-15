<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class PaymentCurrencyRelative extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payment_currency_relative', function (Blueprint $table) {
            $table->bigInteger('post_id')->unsigned();
            $table->bigInteger('relative_id')->unsigned();
            $table->foreign('post_id')
                ->references('id')
                ->on('payments')
                ->onDelete('cascade');
            $table->foreign('relative_id')
                ->references('id')
                ->on('currencies')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('payment_currency_relative');
    }
}
