<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

use App\Http\Controllers\backend\RoomController;
use App\Http\Controllers\backend\EmployeeController;
use App\Http\Controllers\backend\BankController;
use App\Http\Controllers\backend\TenantsConteoller;
use App\Http\Controllers\backend\LeaseController;
use App\Http\Controllers\backend\CancelLeaseController;
use App\Http\Controllers\backend\InvoiceController;
use App\Http\Controllers\backend\PaymentController;

// Admin Login & Logout Routes
Route::get('/login', [App\Http\Controllers\Auth\CustomLoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [App\Http\Controllers\Auth\CustomLoginController::class, 'loginOwner'])->name('login.post');
Route::post('/logout', function (Request $request) {
    // ลบเฉพาะ session ของ admin เท่านั้น ไม่ลบ session ของ user/tenant
    $request->session()->forget('auth_owner');
    return redirect()->route('backend.login')->with('success', 'ออกจากระบบเรียบร้อย');
})->name('logout');

// Admin Dashboard Route
Route::get('/', function () {
    // ตรวจสอบ custom session
    if (!session()->has('auth_owner')) {
        return redirect()->route('backend.login')->with('error', 'กรุณาเข้าสู่ระบบ');
    }
    return view('admin.dashboard');
})->name('dashboard');

// Protected Admin Routes
// Resource Routes
Route::resource('rooms', RoomController::class);
Route::resource('employees', EmployeeController::class);
Route::resource('banks', BankController::class);

// Tenant Routes
Route::get('/tenant' , [TenantsConteoller::class, 'index'])->name('tenants.index');
Route::get('/tenant/create' , [TenantsConteoller::class, 'create'])->name('tenants.create');
Route::post('/tenant/store' , [TenantsConteoller::class, 'store'])->name('tenants.store');
Route::get('/tenant/edit/{id}' , [TenantsConteoller::class, 'edit'])->name('tenants.edit');
Route::put('/tenant/update/{id}' , [TenantsConteoller::class, 'update'])->name('tenants.update');   
Route::delete('/tenant/destroy/{id}' , [TenantsConteoller::class, 'destroy'])->name('tenants.destroy');
Route::get('/tenant/show/{id}', [TenantsConteoller::class, 'show'])->name('tenants.show');

// Lease Routes
Route::get('/leases' , [LeaseController::class, 'index'])->name('leases.index');
Route::get('/leases/create' , [LeaseController::class, 'create'])->name('leases.create');
Route::post('/leases/store' , [LeaseController::class, 'store'])->name('leases.store');
Route::get('/leases/show/{id}', [LeaseController::class, 'show'])->name('leases.show');
Route::get('leases/{id}/cancel', [LeaseController::class, 'cancelForm'])->name('leases.cancel.form');
Route::post('leases/{id}/cancel', [LeaseController::class, 'cancel'])->name('leases.cancel');

// Cancel Lease Routes
Route::get('/cancel_lease' , [CancelLeaseController::class, 'index'])->name('cancel_lease.index');
Route::get('/cancel_lease/show/{id}', [CancelLeaseController::class, 'show'])->name('cancel_lease.show');
Route::post('/cancel-leases/{id}/approve', [CancelLeaseController::class, 'approve'])->name('cancel-leases.approve');
Route::post('/cancel-leases/{id}/reject', [CancelLeaseController::class, 'reject'])->name('cancel-leases.reject');

// Invoice Routes
Route::get('/invoices' , [InvoiceController::class, 'index'])->name('invoices.index');
Route::get('/invoices/create' , [InvoiceController::class, 'create'])->name('invoices.create');
Route::post('/invoices/store' , [InvoiceController::class, 'store'])->name('invoices.store');
Route::get('/invoices/show/{invoice}', [InvoiceController::class, 'show'])->name('invoices.show');
Route::patch('invoices/{invoice}/cancel', [InvoiceController::class, 'cancel'])->name('invoices.cancel');
Route::get('invoices/fetch-lease/{lease}', [InvoiceController::class, 'fetchLease']) ->name('invoices.fetch-lease');
Route::post('/invoices/{invoice}/notify', [InvoiceController::class, 'notify'])->name('invoices.notify');

// Payment Routes
Route::get('/payments', [PaymentController::class, 'index'])->name('payments.index');
Route::get('/payments/{payment}', [PaymentController::class, 'show'])->name('payments.show');
Route::post('/payments/{payment}/status', [PaymentController::class, 'updateStatus'])->name('payments.updateStatus');

// Announcement Routes
use App\Http\Controllers\backend\AnnouncementController;
Route::get('/announcements', [AnnouncementController::class, 'index'])->name('announcements.index');
Route::get('/announcements/create', [AnnouncementController::class, 'create'])->name('announcements.create');
Route::post('/announcements/store', [AnnouncementController::class, 'store'])->name('announcements.store');
Route::get('/announcements/edit/{id}', [AnnouncementController::class, 'edit'])->name('announcements.edit');
Route::put('/announcements/update/{id}', [AnnouncementController::class, 'update'])->name('announcements.update');
Route::delete('/announcements/destroy/{id}', [AnnouncementController::class, 'destroy'])->name('announcements.destroy');