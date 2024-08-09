<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Teacher extends Model
{
    use HasFactory;
    protected $primaryKey = 'teacherID'; 
    public $incrementing = true; // Indicates that this is an auto-incrementing field
    protected $keyType = 'int'; // Ensure it's set to int if using auto-increment
    
    protected $fillable = [
        'userID',
        'name',
        'certification',
        'identityProof',
        'teacherPicture',
        'yearsOfExperience',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'userID');
        
    }
}
