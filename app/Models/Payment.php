<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $table = 'payments';
    protected $primaryKey = 'payment_id';

    public $timestamps = false;

    protected $fillable = [
        'invoice_id',
        'bank_id',
        'method',
        'paid_date',
        'total_amount',
        'pic_slip',
        'status',
        'note',
        'created_by',
    ];

    protected $casts = [
        'paid_date'   => 'date',
        'total_amount'=> 'integer',
        'status'      => 'integer',
        'method'      => 'integer',
    ];

    // ความสัมพันธ์
    public function invoice()
    {
        return $this->belongsTo(Invoice::class, 'invoice_id', 'invoice_id');
    }

    public function bank()
    {
        return $this->belongsTo(Bank::class, 'bank_id', 'bank_id');
    }

    // แปลง method เป็นข้อความ
    public function getMethodLabelAttribute()
    {
        return match ($this->method) {
            1       => $this->bank ? 'โอน - ' . $this->bank->bank_name : 'โอนผ่านธนาคาร',
            2       => 'เงินสด',
            default => 'อื่น ๆ',
        };
    }

    // แปลง status เป็นข้อความ
    public function getStatusLabelAttribute()
    {
        return match ($this->status) {
            0       => 'รอตรวจสอบ',
            1       => 'อนุมัติ',
            2       => 'ปฏิเสธ',
            default => 'ไม่ทราบสถานะ',
        };
    }

    // class สี badge สถานะ
    public function getStatusBadgeClassAttribute()
    {
        return match ($this->status) {
            0       => 'bg-yellow-100 text-yellow-800',
            1       => 'bg-green-100 text-green-800',
            2       => 'bg-red-100 text-red-800',
            default => 'bg-gray-100 text-gray-800',
        };
    }
}
