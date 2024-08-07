<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Counter extends Model
{
    protected $table = 'test'; // Ensure this matches your table name

    // Specify the primary key column if it's not 'id'
    protected $primaryKey = 'name'; // Change 'name' to your actual primary key column

    // Specify if the primary key is not an incrementing integer
    public $incrementing = false;

    // Specify if the primary key is not an integer
    protected $keyType = 'string';

    // Disable timestamps
    public $timestamps = false;

    protected $fillable = ['name', 'counter'];
}
