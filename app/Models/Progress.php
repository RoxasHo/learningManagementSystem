<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Progress extends Model
{
    use HasFactory;
    protected $table="progresses";
    protected $fillable=[
        'chapter_id',
        'student_id',
        'status'
    ];
}
