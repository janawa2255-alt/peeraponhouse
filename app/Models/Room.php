<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    protected $table = 'rooms';    
    protected $primaryKey = 'room_id';  

   
    public $timestamps = true;

    protected $fillable = [
        'room_no',
        'base_rent',
        'status',
        'note',
    ];
        public static $statusText = [
        0 => 'ว่าง',
        1 => 'มีผู้เช่า',

    ];
}
