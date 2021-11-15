<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CasinoTypePaymentRelative extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('casino_type_payment_relative', function (Blueprint $table) {
            $table->bigInteger('post_id')->unsigned();
            $table->bigInteger('relative_id')->unsigned();
            $table->foreign('post_id')
                ->references('id')
                ->on('casinos')
                ->onDelete('cascade');
            $table->foreign('relative_id')
                ->references('id')
                ->on('type_payments')
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
        Schema::dropIfExists('casino_type_payment_relative');
    }
}
