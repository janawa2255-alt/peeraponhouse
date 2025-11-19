<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
{
   
public function index(Request $request)
{
   
    $status = $request->query('status', 'all');

    $query = Payment::with([
        'invoice.expense.lease.tenants',
        'invoice.expense.lease.rooms',
    ]);

    switch ($status) {
        case 'pending':   
            $query->where('status', 0);
            break;
        case 'approved':  
            $query->where('status', 1);
            break;
        case 'rejected':  
            $query->where('status', 2);
            break;
        case 'all':
        default:
       
            break;
    }
    $payments = $query
        ->orderByDesc('paid_date')
        ->paginate(10)
        ->appends(['status' => $status]); 

    return view('payments.index', compact('payments', 'status'));
}

    public function show(Payment $payment)
    {
        $payment->load([
            'invoice.expense.lease.tenants',
        ]);

        return view('payments.show', compact('payment'));
    }
    public function updateStatus(Request $request, Payment $payment)
    {
        $data = $request->validate([
            'status' => 'required|in:1,2',  
            'note'   => 'nullable|string|max:255',
        ]);

        $payment->status     = $data['status'];
        $payment->note       = $data['note'] ?? null;
        $payment->created_by = Auth::user()->name ?? Auth::id();
        $payment->save();

        return redirect()
            ->route('payments.index')
            ->with('success', 'อัปเดตสถานะการชำระเงินเรียบร้อยแล้ว');
    }
}
