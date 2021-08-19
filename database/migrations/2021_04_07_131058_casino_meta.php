<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

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
            $table->string('icon');
            $table->string('license');
            $table->string('bonus');
            $table->string('bonus_wagering');
            $table->string('freespins');
            $table->string('freespins_wagering');
            $table->text('faq');
            $table->text('faq_title');
            $table->text('methods_pay');
            $table->text('methods_payout');
            $table->string('min_deposit');
            $table->string('min_payout');
            $table->integer('rating')->default(0);
            $table->string('ref');
            $table->unique('post_id');
            $table->boolean('regular_offers')->default(1);
            $table->boolean('live_chat')->default(1);
            $table->boolean('live_casino')->default(1);
            $table->boolean('vip_program')->default(1);
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
        Schema::dropIfExists('casino_meta');
    }
}
