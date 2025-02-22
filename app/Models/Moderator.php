<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

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
        'certification',
        'identityProof',
        'moderatorPicture',
        'userID',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'userID');
    }

    public function getCertificationAttribute($value)
{
    return Storage::url($value);
}

public function getIdentityProofAttribute($value)
{
    return Storage::url($value);
}

public function getModeratorPictureAttribute($value)
{
    return Storage::url($value);
}
}
