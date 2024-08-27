<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Moderator extends Model
{
    use HasFactory;
    protected $primaryKey = 'moderatorID';
    public $incrementing = true; // Indicates that this is an auto-incrementing field
    protected $keyType = 'int';

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($moderator) {
            $moderator->approval_token = Str::random(40); // Generate a unique token
        });
    }
    protected $fillable = [
        'name',
        'blacklistUser',
        'reportsHandled',
        'approval_token',
        'certification',
        'identityProof',
        'moderatorPicture',
        'status',
        'rejection_reason',
        'userID',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'userID');
    }
}
