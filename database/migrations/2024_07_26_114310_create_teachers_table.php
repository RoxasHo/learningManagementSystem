<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('teachers', function (Blueprint $table) {
            $table->id('teacherID'); // 默认是 unsignedBigInteger
            $table->string('name', 50); // 添加教师名称字段
            $table->integer('yearsOfExperience')->nullable()->unsigned();
            $table->string('certification')->nullable(); // 存储证书路径
            $table->string('identityProof')->nullable(); // 存储身份证明路径
            $table->string('picture')->nullable(); // 存储图片路径
            $table->unsignedBigInteger('userID'); // 确保与 users 表主键一致
            $table->foreign('userID')->references('id')->on('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('teachers');
    }
};
