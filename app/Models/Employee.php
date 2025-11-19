<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    protected $primaryKey = 'emp_id';

    public $timestamps = true;
    
    protected $fillable = [
        'name',
        'phone',
        'email',
        'username',
        'password',
        'status',
        'avatar_path'
    ];

    protected $hidden = ['password'];
}

