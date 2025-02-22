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
        Schema::create('enrollments', function (Blueprint $table) {
            $table->id(); // Primary key
            $table->foreignId('student_id')->constrained('users')->onDelete('cascade'); // Assuming 'users' table for students
            $table->foreignId('course_id')->constrained('courses')->onDelete('cascade'); // Assuming 'courses' table
            $table->timestamp('last_accessed')->nullable(); // Last accessed attribute
            $table->boolean('status')->default(true); // Status, true for enrolled, false for not enrolled
            $table->timestamps(); // Created at and updated at timestamps
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
