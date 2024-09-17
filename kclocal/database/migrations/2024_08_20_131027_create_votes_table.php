<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('votes', function (Blueprint $table) {
            $table->id('vote_id');
            $table->unsignedBigInteger('userID'); 
            $table->bigInteger('post_id')->unsigned();
            $table->bigInteger('comment_id')->unsigned()->nullable();
            $table->enum('type', ['like', 'dislike']);
            $table->foreign('post_id')->references('id')->on('posts');
            $table->foreign('comment_id')->references('id')->on('comments');
            $table->timestamps();
            $table->foreign('userID')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('votes');
    }
};
