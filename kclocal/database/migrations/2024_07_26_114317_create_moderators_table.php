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
            $table->string('approval_token')->nullable()->unique();

            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending'); // Add status column
            $table->text('rejection_reason')->nullable();
            $table->string('certification')->nullable(); // Certification file path
            $table->string('identityProof')->nullable(); // Identity proof file path
            $table->string('moderatorPicture')->nullable(); // Moderator picture path
            $table->unsignedBigInteger('userID'); // Foreign key
            $table->foreign('userID')->references('id')->on('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('moderators');
    }
};
