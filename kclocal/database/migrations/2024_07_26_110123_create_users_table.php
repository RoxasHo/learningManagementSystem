<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id(); // Auto-incrementing primary key
            $table->string('name', 50);
            $table->string('email', 100)->unique();
            $table->string('password');
            $table->string('gender')->nullable();
            $table->date('dateOfBirth')->nullable();
            $table->string('contactNumber')->nullable()->unique();
            $table->enum('role', ['Student', 'Teacher', 'Moderator', 'Superuser']);
            $table->rememberToken();
            $table->string('token')->nullable();
            $table->timestamp('token_created_at')->nullable();
            $table->text('profile')->nullable();
            $table->string('feedback')->nullable();
            $table->integer('point')->nullable()->unsigned();
            $table->timestamp('last_login_at')->nullable();
            $table->timestamps(); // Created at & Updated at
        });
    }

    public function down()
    {
        Schema::dropIfExists('users');
    }
};
