<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class BonusCategory extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bonus_category', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('parent_id')->default(0);
            $table->string('post_type')->default('bonus/category');
            $table->enum('status', ['public', 'hide', 'basket'])->default('public');
            $table->string('permalink');
            $table->string('slug')->default('bonus/category');
            $table->string('title');
            $table->string('thumbnail', 300);
            $table->string('short_desc', 500);
            $table->string('h1');
            $table->string('meta_title');
            $table->string('description');
            $table->string('keywords');
            $table->longText('content');
            $table->longText('content');
            $table->integer('lang')->default(1);
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent();
            $table->unique('permalink');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bonus_category');
    }
}
