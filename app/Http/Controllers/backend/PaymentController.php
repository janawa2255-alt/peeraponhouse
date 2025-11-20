<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Mail\PaymentApproved;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

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
        ->orderByDesc('payment_id')
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
        // ตรวจสอบว่าสถานะถูกอัปเดตไปแล้วหรือไม่ (ไม่ใช่ 0 = รอตรวจสอบ)
        if ($payment->status != 0) {
            return redirect()
                ->route('backend.payments.show', $payment)
                ->with('error', 'การชำระเงินนี้ได้รับการดำเนินการไปแล้ว');
        }

        $data = $request->validate([
            'status' => 'required|in:1,2',  
            'note'   => 'nullable|string|max:255',
        ]);

        $payment->status     = $data['status'];
        $payment->note       = $data['note'] ?? null;
        $payment->created_by = Auth::user()->name ?? Auth::id();
        $payment->save();

        // อัปเดตสถานะ Invoice เมื่ออนุมัติการชำระเงิน
        if ($data['status'] == 1) {
            // status = 1 คือ อนุมัติ -> อัปเดต Invoice เป็น ชำระแล้ว (status = 1)
            $payment->load('invoice'); // โหลด relationship
            $invoice = $payment->invoice;
            
            if ($invoice) {
                $invoice->status = 1; // 1 = ชำระแล้ว
                $invoice->save();
                
                \Log::info('Invoice updated', [
                    'invoice_id' => $invoice->invoice_id,
                    'new_status' => $invoice->status
                ]);
            } else {
                \Log::warning('Invoice not found for payment', ['payment_id' => $payment->payment_id]);
            }

            // ส่งอีเมลแจ้งเตือนผู้เช่า
            try {
                $payment->load(['invoice.expense.lease.tenants', 'invoice.expense.lease.rooms', 'bank']);
                $tenantEmail = $payment->invoice->expense->lease->tenants->email ?? null;
                
                if ($tenantEmail) {
                    Mail::to($tenantEmail)->send(new PaymentApproved($payment));
                }
            } catch (\Exception $e) {
                // Log error but don't stop the process
                \Log::error('Failed to send payment approval email: ' . $e->getMessage());
            }
        } elseif ($data['status'] == 2) {
            // status = 2 คือ ปฏิเสธ -> อัปเดต Invoice กลับเป็น รอชำระ (status = 0)
            $payment->load('invoice'); // โหลด relationship
            $invoice = $payment->invoice;
            
            if ($invoice) {
                $invoice->status = 0; // 0 = รอชำระ
                $invoice->save();
                
                \Log::info('Invoice rejected', [
                    'invoice_id' => $invoice->invoice_id,
                    'new_status' => $invoice->status
                ]);
            } else {
                \Log::warning('Invoice not found for payment', ['payment_id' => $payment->payment_id]);
            }
        }

        return redirect()
            ->route('backend.payments.index')
            ->with('success', 'อัปเดตสถานะการชำระเงินเรียบร้อยแล้ว');
    }
}
