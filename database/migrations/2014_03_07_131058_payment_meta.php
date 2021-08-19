<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class PaymentMeta extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payment_meta', function (Blueprint $table) {
            $table->bigInteger('post_id')->unsigned();
            $table->text('faq');
            $table->text('faq_title');
            $table->text('short_text');
            $table->integer('rating')->default(0);
            $table->unique('post_id');
            $table->foreign('post_id')
                ->references('id')
                ->on('posts')
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
        Schema::dropIfExists('payment_meta');
    }
}
