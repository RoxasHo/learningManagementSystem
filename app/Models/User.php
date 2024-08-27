<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\Hash;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'email',
        'name',
        'password',
        'gender',
        'dateOfBirth',
        'contactNumber',
        'role',
        'profile',
        'feedback',
        'remember_token',
        'token',
        'token_created_at',
        'point',
        'last_login_at',
        'registeredAt',
    ];

    public $timestamps = true;

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'last_login_at' => 'datetime',
    ];

    public function moderator()
    {
        return $this->hasOne(Moderator::class, 'userID');
    }
    public function student()
{
    return $this->hasOne(Student::class, 'userID');
}
public function teacher()
{
    return $this->hasOne(Teacher::class, 'userID');
}


    protected static function boot()
    {
        parent::boot();

        static::creating(function ($user) {
            if (User::where('email', $user->email)->exists()) {
                throw new \Exception('Email already exists.');
            }

            if (User::where('name', $user->name)->exists()) {
                throw new \Exception('Name already exists.');
            }

            if (User::where('contactNumber', $user->contactNumber)->exists()) {
                throw new \Exception('Contact number already exists.');
            }

            if (User::where('password', Hash::make($user->password))->exists()) {
                throw new \Exception('Password already exists.');
            }
        });
    }
}
