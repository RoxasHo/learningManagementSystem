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
        Schema::create('followed_tags', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('userID'); 
            $table->string('tag');
            $table->timestamps();
            $table->foreign('userID')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('followed_tags');
    }
};
