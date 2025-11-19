<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Bank extends Model
{
    protected $primaryKey = 'bank_id';

    protected $fillable = [
        'bank_code',
        'bank_name',
        'account_name',
        'number',
        'qrcode_pic',
        'status',
    ];
}
