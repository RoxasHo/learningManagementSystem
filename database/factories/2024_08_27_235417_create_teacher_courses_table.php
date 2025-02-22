<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTeacherCoursesTable extends Migration
{
    public function up()
    {
        Schema::create('teacher_courses', function (Blueprint $table) {
            $table->id(); // Use id() method to create the primary key
            $table->unsignedBigInteger('teacher_id');
            $table->unsignedBigInteger('course_id');
            $table->string('status');
            $table->timestamps();

            $table->foreign('teacher_id')->references('teacherID')->on('teachers')->onDelete('cascade');
            $table->foreign('course_id')->references('id')->on('courses')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('teacher_courses');
    }
}
