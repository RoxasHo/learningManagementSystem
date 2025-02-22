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
        Schema::table('questionnaire_responses', function (Blueprint $table) {
            $table->string('education_background')->nullable(); // E.g., primary, secondary, tertiary, graduated
            $table->boolean('programming_experience')->default(false); // Yes or No
            $table->json('learned_languages')->nullable(); // Store languages learned
            $table->json('interested_categories')->nullable(); // Store selected categories
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('questionnaire_responses', function (Blueprint $table) {
            //
        });
    }
};
