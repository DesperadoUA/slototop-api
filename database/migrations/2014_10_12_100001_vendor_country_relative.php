<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class VendorCountryRelative extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vendor_country_relative', function (Blueprint $table) {
            $table->bigInteger('post_id')->unsigned();
            $table->bigInteger('relative_id')->unsigned();
            $table->foreign('post_id')
                ->references('id')
                ->on('vendors')
                ->onDelete('cascade');
            $table->foreign('relative_id')
                ->references('id')
                ->on('countries')
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
        Schema::dropIfExists('vendor_country_relative');
    }
}
