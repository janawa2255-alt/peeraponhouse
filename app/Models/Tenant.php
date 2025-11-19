<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tenant extends Model
{
    protected $table = 'tenants';    
    protected $primaryKey = 'tenant_id';  

   
    public $timestamps = true;

    protected $fillable = [
        'name',
        'phone',
        'email',
        'id_card',
        'address',
        'username',
        'password',
        'avatar_path',
        'status',
        'created_at',
        'updated_at',
    ];
    
    public static $statusText = [
        0 => 'เช่าอยู่',
        1 => 'ยกเลิก',
    ];
     protected $hidden = ['password'];
}
 
