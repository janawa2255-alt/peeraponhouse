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
    
    /**
     * Relationship: Tenant has many Leases
     */
    public function leases()
    {
        return $this->hasMany(Lease::class, 'tenant_id', 'tenant_id');
    }
    
    /**
     * Get active leases (status = 1)
     */
    public function activeLeases()
    {
        return $this->hasMany(Lease::class, 'tenant_id', 'tenant_id')
                    ->where('status', 1);
    }
}
 
