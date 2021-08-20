<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class TypePaymentMeta extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('type_payment_meta', function (Blueprint $table) {
            $table->bigInteger('post_id')->unsigned();
            $table->string('sub_title');
            $table->unique('post_id');
            $table->foreign('post_id')
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
        Schema::dropIfExists('type_payment_meta');
    }
}
