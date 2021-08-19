<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class PokerMeta extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('poker_meta', function (Blueprint $table) {
            $table->bigInteger('post_id')->unsigned();
            $table->longText('faq');
            $table->longText('reviews');
            $table->string('rakeback');
            $table->string('network');
            $table->integer('rating');
            $table->string('ref');
            $table->string('phone');
            $table->string('min_deposit');
            $table->string('min_payments');
            $table->string('email');
            $table->string('year');
            $table->string('site');
            $table->string('withdrawal');
            $table->unique('post_id');
            $table->foreign('post_id')
                ->references('id')
                ->on('pokers')
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
        Schema::dropIfExists('poker_meta');
    }
}
