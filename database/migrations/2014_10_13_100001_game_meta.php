<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class GameMeta extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('game_meta', function (Blueprint $table) {
            $table->bigInteger('post_id')->unsigned();
            $table->longText('banner');
            $table->longText('gallery');
            $table->string('jackpot');
            $table->string('risk_game');
            $table->string('max_gain');
            $table->string('max_bet');
            $table->string('min_bet');
            $table->text('iframe');
            $table->longText('characters');
            $table->longText('details');
            $table->unique('post_id');
            $table->foreign('post_id')
                ->references('id')
                ->on('games')
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
        Schema::dropIfExists('game_meta');
    }
}
