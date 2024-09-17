<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('comments', function (Blueprint $table) {
            $table->unsignedBigInteger('parent_comment_id')->nullable()->after('post_id');
    
            // Optional: Create a foreign key relationship to the comments table itself
            $table->foreign('parent_comment_id')->references('comment_id')->on('comments')->onDelete('cascade');
        });
    }
    
    public function down()
    {
        Schema::table('comments', function (Blueprint $table) {
            $table->dropForeign(['parent_comment_id']);
            $table->dropColumn('parent_comment_id');
        });
    }
    
};
