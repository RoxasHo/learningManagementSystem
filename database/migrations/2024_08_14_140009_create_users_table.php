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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name', 50);
            $table->string('email', 100)->unique();
            $table->string('password');
            $table->string('gender')->nullable();
            $table->date('dateOfBirth')->nullable();
            $table->string('contactNumber')->nullable();
            $table->enum('role', ['Student', 'Teacher', 'Moderator','superuser']);
            $table->rememberToken();
            $table->string('token')->nullable();
            $table->timestamp('token_created_at')->nullable();
            $table->text('profile')->nullable();
            $table->string('feedback')->nullable();
            $table->integer('point')->nullable()->unsigned();
            $table->timestamp('last_login_at')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
