<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Quizz extends Model
{
    use HasFactory;
    protected $table="quizz_";
    protected $fillable=[
        'content',
        'chapter_id'
    ];
}
