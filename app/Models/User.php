<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{

    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
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

    public $timestamps = true; // Ensure timestamps are managed by Laravel

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];


    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */

    /* public function student()
    {
        return $this->hasOne(Student::class, 'userID');
    }

    public function teacher()
    {
        return $this->hasOne(Teacher::class, 'userID');
    }
        */


    public function moderator()
    {
        return $this->hasOne(Moderator::class, 'userID');
    }

    protected $casts = [
        'email_verified_at' => 'datetime',
        'last_login_at' => 'datetime', // 确保该属性被正确转化为 Carbon 实例

    ];

    public function student()
    {
        return $this->hasOne(Student::class, 'userID');
    }

    public function teacher()
    {
        return $this->hasOne(Teacher::class, 'userID');
    }
}
