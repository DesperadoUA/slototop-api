<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class TypeBonusMeta extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('type_bonus_meta', function (Blueprint $table) {
            $table->bigInteger('post_id')->unsigned();
            $table->string('sub_title');
            $table->unique('post_id');
            $table->foreign('post_id')
                ->references('id')
                ->on('type_bonuses')
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
        Schema::dropIfExists('type_bonus_meta');
    }
}
