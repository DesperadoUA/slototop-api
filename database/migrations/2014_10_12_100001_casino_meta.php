<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CasinoMeta extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('casino_meta', function (Blueprint $table) {
            $table->bigInteger('post_id')->unsigned();
            $table->longText('faq');
            $table->longText('reviews');
            $table->boolean('close');
            $table->integer('rating');
            $table->string('ref');
            $table->string('phone');
            $table->string('min_deposit');
            $table->string('min_payments');
            $table->string('email');
            $table->string('chat');
            $table->string('year');
            $table->string('site');
            $table->string('withdrawal');
            $table->string('number_games');
            $table->unique('post_id');
            $table->foreign('post_id')
                ->references('id')
                ->on('casinos')
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
        Schema::dropIfExists('casino_meta');
    }
}
