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
use App\Http\Controllers\backend\ReportController;


Route::get('/login', [App\Http\Controllers\Auth\CustomLoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [App\Http\Controllers\Auth\CustomLoginController::class, 'loginOwner'])->name('login.post');
Route::post('/logout', function (Request $request) {

    $request->session()->forget('auth_owner');
    return redirect()->route('backend.login')->with('success', 'ออกจากระบบเรียบร้อย');
})->name('logout');


Route::middleware(['auth.owner'])->group(function () {

    Route::get('/', function () {
        return view('admin.dashboard');
    })->name('dashboard');

    Route::get('/rooms', [RoomController::class, 'index'])->name('rooms.index');
    Route::get('/rooms/create', [RoomController::class, 'create'])->name('rooms.create');
    Route::post('/rooms/store', [RoomController::class, 'store'])->name('rooms.store');
    Route::get('/rooms/edit/{id}', [RoomController::class, 'edit'])->name('rooms.edit');
    Route::put('/rooms/update/{id}', [RoomController::class, 'update'])->name('rooms.update');
    Route::delete('/rooms/destroy/{id}', [RoomController::class, 'destroy'])->name('rooms.destroy');

    Route::get('/employees', [EmployeeController::class, 'index'])->name('employees.index');
    Route::get('/employees/create', [EmployeeController::class, 'create'])->name('employees.create');
    Route::post('/employees/store', [EmployeeController::class, 'store'])->name('employees.store');
    Route::get('/employees/edit/{id}', [EmployeeController::class, 'edit'])->name('employees.edit');
    Route::put('/employees/update/{id}', [EmployeeController::class, 'update'])->name('employees.update');
    Route::delete('/employees/destroy/{id}', [EmployeeController::class, 'destroy'])->name('employees.destroy');


    Route::get('/banks', [BankController::class, 'index'])->name('banks.index');
    Route::get('/banks/create', [BankController::class, 'create'])->name('banks.create');
    Route::post('/banks/store', [BankController::class, 'store'])->name('banks.store');
    Route::get('/banks/edit/{id}', [BankController::class, 'edit'])->name('banks.edit');
    Route::put('/banks/update/{id}', [BankController::class, 'update'])->name('banks.update');
    Route::delete('/banks/destroy/{id}', [BankController::class, 'destroy'])->name('banks.destroy');


    Route::get('/tenant' , [TenantsConteoller::class, 'index'])->name('tenants.index');
    Route::get('/tenant/create' , [TenantsConteoller::class, 'create'])->name('tenants.create');
    Route::post('/tenant/store' , [TenantsConteoller::class, 'store'])->name('tenants.store');
    Route::get('/tenant/edit/{id}' , [TenantsConteoller::class, 'edit'])->name('tenants.edit');
    Route::put('/tenant/update/{id}' , [TenantsConteoller::class, 'update'])->name('tenants.update');   
    Route::delete('/tenant/destroy/{id}' , [TenantsConteoller::class, 'destroy'])->name('tenants.destroy');
    Route::get('/tenant/show/{id}', [TenantsConteoller::class, 'show'])->name('tenants.show');

  
    Route::get('/leases' , [LeaseController::class, 'index'])->name('leases.index');
    Route::get('/leases/create' , [LeaseController::class, 'create'])->name('leases.create');
    Route::post('/leases/store' , [LeaseController::class, 'store'])->name('leases.store');
    Route::get('/leases/show/{id}', [LeaseController::class, 'show'])->name('leases.show');
    Route::get('leases/{id}/cancel', [LeaseController::class, 'cancelForm'])->name('leases.cancel.form');
    Route::post('leases/{id}/cancel', [LeaseController::class, 'cancel'])->name('leases.cancel');
    Route::delete('/leases/destroy/{id}', [LeaseController::class, 'destroy'])->name('leases.destroy');

  
    Route::get('/cancel_lease' , [CancelLeaseController::class, 'index'])->name('cancel_lease.index');
    Route::get('/cancel_lease/show/{id}', [CancelLeaseController::class, 'show'])->name('cancel_lease.show');
    Route::post('/cancel-leases/{id}/approve', [CancelLeaseController::class, 'approve'])->name('cancel-leases.approve');
    Route::post('/cancel-leases/{id}/reject', [CancelLeaseController::class, 'reject'])->name('cancel-leases.reject');


    Route::get('/invoices' , [InvoiceController::class, 'index'])->name('invoices.index');
    Route::get('/invoices/create' , [InvoiceController::class, 'create'])->name('invoices.create');
    Route::post('/invoices/store' , [InvoiceController::class, 'store'])->name('invoices.store');
    Route::get('/invoices/show/{invoice}', [InvoiceController::class, 'show'])->name('invoices.show');
    Route::patch('invoices/{invoice}/cancel', [InvoiceController::class, 'cancel'])->name('invoices.cancel');
    Route::get('invoices/fetch-lease/{lease}', [InvoiceController::class, 'fetchLease']) ->name('invoices.fetch-lease');
    Route::post('/invoices/{invoice}/notify', [InvoiceController::class, 'notify'])->name('invoices.notify');
    Route::delete('/invoices/destroy/{id}', [InvoiceController::class, 'destroy'])->name('invoices.destroy');

  
    Route::get('/payments', [PaymentController::class, 'index'])->name('payments.index');
    Route::get('/payments/{payment}', [PaymentController::class, 'show'])->name('payments.show');
    Route::post('/payments/{payment}/status', [PaymentController::class, 'updateStatus'])->name('payments.updateStatus');

    Route::get('/announcements', [App\Http\Controllers\backend\AnnouncementController::class, 'index'])->name('announcements.index');
    Route::get('/announcements/create', [App\Http\Controllers\backend\AnnouncementController::class, 'create'])->name('announcements.create');
    Route::post('/announcements/store', [App\Http\Controllers\backend\AnnouncementController::class, 'store'])->name('announcements.store');
    Route::get('/announcements/edit/{id}', [App\Http\Controllers\backend\AnnouncementController::class, 'edit'])->name('announcements.edit');
    Route::put('/announcements/update/{id}', [App\Http\Controllers\backend\AnnouncementController::class, 'update'])->name('announcements.update');
    Route::delete('/announcements/destroy/{id}', [App\Http\Controllers\backend\AnnouncementController::class, 'destroy'])->name('announcements.destroy');
    
   
    Route::get('/reports/income', [ReportController::class, 'income'])->name('reports.income');
    Route::get('/reports/outstanding', [ReportController::class, 'outstanding'])->name('reports.outstanding');
    
}); 