<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuestionnaireResponse extends Model
{
    use HasFactory;

    // Define the table name if it's not the plural of the model name
    protected $table = 'questionnaire_responses';

    // Specify the fillable attributes
    protected $fillable = [
        'user_id',
        'education_background',
        'programming_experience',
        'learned_languages',
        'interested_categories',
    ];

    // Define the relationship with the User model
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
