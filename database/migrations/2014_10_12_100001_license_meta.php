<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class LicenseMeta extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('license_meta', function (Blueprint $table) {
            $table->bigInteger('post_id')->unsigned();
            $table->string('sub_title');
            $table->unique('post_id');
            $table->foreign('post_id')
                ->references('id')
                ->on('licenses')
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
        Schema::dropIfExists('license_meta');
    }
}
