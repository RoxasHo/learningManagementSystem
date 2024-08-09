<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

  public function up()
    {
        Schema::create('moderators', function (Blueprint $table) {
            $table->id('moderatorID'); // Auto-incrementing ID
            $table->string('name', 50); // Name field
            $table->boolean('blacklistUser')->default(false); // Default value
            $table->integer('reportsHandled')->nullable()->unsigned(); // Nullable field
            $table->string('referralCode'); // Referral Code
            $table->unsignedBigInteger('userID'); // Foreign key
            $table->string('moderatorPicture')->nullable()->change;
            $table->foreign('userID')->references('id')->on('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('moderators');
    }
};
