<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Moderator extends Model
{
    use HasFactory;
    protected $primaryKey = 'moderatorID';
    public $incrementing = true; // Indicates that this is an auto-incrementing field
    protected $keyType = 'int';

    protected $fillable = [
        'name',
        'blacklistUser',
        'reportsHandled',
        'referralCode',
        'moderatorPicture',
        'userID',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'userID');
    }
}
