<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    protected $table = 'invoices';
    protected $primaryKey = 'invoice_id';

    public $timestamps = false ; 

    protected $fillable = [
        'ex_id',
        'invoice_code',
        'invoice_data',
        'due_date',
        'status',
        'created_by',
    ];

    protected $casts = [
        'invoice_data' => 'date',
        'due_date'     => 'date',
        'status'       => 'integer',
    ];

    public function expense()
    {
        return $this->belongsTo(Expense::class, 'ex_id', 'ex_id');
    }

    public function payments()
    {
        return $this->hasMany(Payment::class, 'invoice_id', 'invoice_id');
    }
}
