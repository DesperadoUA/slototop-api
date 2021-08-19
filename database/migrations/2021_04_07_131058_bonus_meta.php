<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

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
            $table->string('sum');
            $table->string('wagering');
            $table->integer('rating')->default(0);
            $table->boolean('show_on_main')->default(0);
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
        Schema::dropIfExists('bonus_meta');
    }
}
