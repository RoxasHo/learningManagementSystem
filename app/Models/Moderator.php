<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Moderator extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'blacklistUser',
        'reportsHandled',
        'preferred_code',
        'userID'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'userID');
    }
}
