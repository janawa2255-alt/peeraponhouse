<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

use App\Http\Controllers\Tenant\TenantLeaseController;
use App\Http\Controllers\Tenant\TenantDashboardController;
use App\Http\Controllers\Tenant\TenantInvoiceController;
use App\Http\Controllers\Tenant\TenantPaymentController;
use App\Http\Controllers\Tenant\TenantProfileController;
use App\Http\Controllers\Auth\CustomLoginController;

/*
|--------------------------------------------------------------------------
| User/Tenant Routes (Web Routes)
|--------------------------------------------------------------------------
| Routes สำหรับผู้เช่าที่ใช้งานระบบ
|
*/

// หน้าแรก - User Dashboard
Route::get('/', [TenantDashboardController::class, 'index'])->name('home');

// Authentication Routes
Route::get('/login', [CustomLoginController::class, 'showLoginForm'])->name('login');
Route::post('/login/tenant', [CustomLoginController::class, 'loginTenant'])->name('login.tenant');
Route::post('/login/owner', [CustomLoginController::class, 'loginOwner'])->name('login.owner');
Route::post('/logout', function (Request $request) {
    $request->session()->forget('auth_tenant');
    $request->session()->forget('auth_owner');
    return redirect()->route('login')->with('success', 'ออกจากระบบเรียบร้อย');
})->name('logout');

// User/Tenant Routes (ต้อง login เป็น tenant)
// Lease (สัญญาเช่า)
Route::get('/lease', [TenantLeaseController::class, 'showCurrent'])->name('lease.show');
Route::post('/lease/cancel/{leaseId}', [TenantLeaseController::class, 'requestCancel'])->name('tenant.lease.cancel.request');

// Invoices (ใบแจ้งหนี้)
Route::get('/invoices', [TenantInvoiceController::class, 'index'])->name('invoices');
Route::get('/invoices/{id}', [TenantInvoiceController::class, 'show'])->name('invoices.show');

// Payments (ประวัติการชำระเงิน)
Route::get('/payments', [TenantPaymentController::class, 'index'])->name('payments');
Route::get('/payments/{id}', [TenantPaymentController::class, 'show'])->name('payments.show');
Route::get('/payments/create/{invoiceId}', [TenantPaymentController::class, 'create'])->name('payments.create');
Route::post('/payments/store', [TenantPaymentController::class, 'store'])->name('payments.store');

// Profile (โปรไฟล์ส่วนตัว)
Route::get('/profile', [TenantProfileController::class, 'show'])->name('profile');
Route::post('/profile', [TenantProfileController::class, 'update'])->name('profile.update');