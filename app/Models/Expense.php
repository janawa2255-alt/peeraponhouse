<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    protected $table = 'expenses';
    protected $primaryKey = 'ex_id';
    public $timestamps = true; 

    // ✅ ใส่ให้ครบทุกคอลัมน์ที่เราใช้ใน Expense::create()
    protected $fillable = [
        'lease_id',
        'month',
        'year',
        'prev_water',
        'curr_water',
        'water_units',
        'water_rate',
        'water_total',
        'elec_total',
        'room_rent',
        'discount',     
        'total_amount',  
        'pic_water',
        'pic_elec',
    ];

    public function lease()
    {
        return $this->belongsTo(Lease::class, 'lease_id', 'lease_id');
    }

    public function invoice()
    {
        return $this->hasOne(Invoice::class, 'ex_id', 'ex_id');
    }
}
