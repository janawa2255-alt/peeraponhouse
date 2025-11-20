<?php

namespace App\Models;
use App\Models\CancelLease;
use Illuminate\Database\Eloquent\Model;

class Lease extends Model
{
    protected $table = 'leases';
    protected $primaryKey = 'lease_id';

    public $timestamps = true;

    protected $fillable = [
        'tenant_id',
        'room_id',
        'start_date',
        'end_date',
        'rent_amount',
        'deposit',
        'note', 
        'pic_tenant',   
        'status',
    ];

    protected $casts = [
        'start_date'  => 'date',
        'end_date'    => 'date',
        'rent_amount' => 'integer',
        'deposit'     => 'integer',
        'note'        => 'string',
        'status'      => 'integer',
    ];

    public function tenant()
    {
        return $this->belongsTo(Tenant::class, 'tenant_id', 'tenant_id');
    }

    public function tenants()
    {
        return $this->belongsTo(Tenant::class, 'tenant_id', 'tenant_id');
    }

    public function rooms()
    {
        return $this->belongsTo(Room::class, 'room_id', 'room_id');
    }
       public function cancelLeases()
    {
        return $this->hasMany(CancelLease::class, 'lease_id', 'lease_id');
    }
        public function expenses()
    {
        return $this->hasMany(Expense::class, 'lease_id', 'lease_id');
    }

    public function latestExpense()
    {
        return $this->hasOne(Expense::class, 'lease_id', 'lease_id')->latestOfMany('created_at');
    }

}
