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
        Schema::create('reports', function (Blueprint $table) {
            $table->id('report_id');
            $table->unsignedBigInteger('userID'); 
            $table->foreignId('post_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('comment_id')->nullable()->constrained()->onDelete('cascade');
            $table->string('report_type')->nullable(); 
            $table->text('custom_content')->nullable();
            $table->foreign('post_id')->references('id')->on('posts');
            $table->foreign('comment_id')->references('id')->on('comments');
            $table->timestamps();
            $table->foreign('userID')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reports');
    }
};
