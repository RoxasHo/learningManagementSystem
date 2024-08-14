<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('teachers', function (Blueprint $table) {
            $table->id('teacherID'); // 自动递增的主键
            $table->string('name', 50); // 教师名称
            $table->integer('yearsOfExperience')->nullable()->unsigned();
            $table->string('certification')->nullable(); // 证书路径
            $table->string('identityProof')->nullable(); // 身份证明路径
            $table->string('teacherPicture')->nullable()->change; // 图片路径
            $table->unsignedBigInteger('userID'); // 与 users 表主键一致
            $table->foreign('userID')->references('id')->on('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('teachers');
    }
};
