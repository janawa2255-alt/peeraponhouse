<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CancelLease extends Model
{
    protected $table = 'cancel_lease';       
    protected $primaryKey = 'cancel_id';      

    public $timestamps = false;               

    protected $fillable = [
        'lease_id',
        'request_date',
        'reason',
        'status',
        'note_owner',
        'created_at',
        'created_by',
    ];

    // ความสัมพันธ์ไปยังสัญญาเช่า
    public function lease()
    {
        return $this->belongsTo(Lease::class, 'lease_id', 'lease_id');
    }
}