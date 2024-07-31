<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    public function up() {
        Schema::create('moderators', function (Blueprint $table) {
            $table->id('moderatorID'); // 默认是 unsignedBigInteger
            $table->string('name', 50); // 添加名称字段

            $table->boolean('blacklistUser');
            $table->integer('reportsHandled')->nullable()->unsigned();
            $table->string('preferred_code');
            $table->unsignedBigInteger('userID'); // 确保与 users 表主键一致
            $table->foreign('userID')->references('id')->on('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down() {
        Schema::dropIfExists('moderators');
    }
};
