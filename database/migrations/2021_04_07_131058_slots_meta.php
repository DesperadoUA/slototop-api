<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class SlotMeta extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('slot_meta', function (Blueprint $table) {
            $table->bigInteger('post_id')->unsigned();
            $table->string('rtp');
            $table->string('icon');
            $table->integer('rating')->default(0);
            $table->string('min_bet');
            $table->string('max_bet');
            $table->string('pay_lines');
            $table->string('reels');
            $table->enum('volatility', ['high', 'medium', 'low'])->default('medium');
            $table->boolean('bonus_rounds')->default(1);
            $table->boolean('free_spins')->default(1);
            $table->boolean('scatters')->default(1);
            $table->boolean('wild_symbol')->default(1);
            $table->string('ref');
            $table->string('number_rows');
            $table->string('type_game');
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
        Schema::dropIfExists('slot_meta');
    }
}
