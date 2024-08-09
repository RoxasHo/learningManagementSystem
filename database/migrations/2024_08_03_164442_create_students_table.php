<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('students', function (Blueprint $table) {
            $table->id('studentID'); // 默认是 unsignedBigInteger
            $table->string('name', 50); // 添加名称字段
            $table->string('reportComment', 255)->nullable();
            $table->decimal('progress', 5, 2)->nullable();
            $table->string('interests')->nullable();
             $table->string('studentPicture')->nullable()->change;
            $table->unsignedBigInteger('userID'); // 确保与 users 表主键一致
            $table->foreign('userID')->references('id')->on('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('students');
        
    }
};
