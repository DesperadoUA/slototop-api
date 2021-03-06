<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class BonusMeta extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bonus_meta', function (Blueprint $table) {
            $table->bigInteger('post_id')->unsigned();
            $table->boolean('close');
            $table->string('ref');
            $table->string('wager');
            $table->string('number_use');
            $table->string('value_bonus');
            $table->unique('post_id');
            $table->foreign('post_id')
                ->references('id')
                ->on('bonuses')
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
        Schema::dropIfExists('bonus_meta');
    }
}
