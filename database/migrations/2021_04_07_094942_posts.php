<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Posts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('post_type');
            $table->enum('status', ['public', 'hide', 'basket'])->default('public');
            $table->string('permalink');
            $table->string('slug');
            $table->string('title');
            $table->string('thumbnail', 300);
            $table->string('short_desc', 500);
            $table->string('h1');
            $table->string('meta_title');
            $table->string('description');
            $table->string('keywords');
            $table->longText('content');
            $table->integer('lang')->default(1);
            $table->timestamp('create_at')->useCurrent();
            $table->timestamp('update_at')->useCurrent();
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
        Schema::dropIfExists('posts');
    }
}
