<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\backend\RoomController;
use App\Http\Controllers\backend\EmployeeController;
use App\Http\Controllers\backend\BankController;
use App\Http\Controllers\backend\TenantsConteoller;
use App\Http\Controllers\backend\LeaseController;
use App\Http\Controllers\backend\CancelLeaseController;
use App\Http\Controllers\backend\InvoiceController;
use App\Http\Controllers\backend\PaymentController;





Route::get('/', function () {
    return view('welcome');
});


Route::middleware(['auth'])
    ->prefix('backend')
    ->group(function () {
        


Route::resource('rooms', RoomController::class);
Route::resource('employees', EmployeeController::class);
Route::resource('banks', BankController::class);




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


Route::get('/cancel_lease' , [CancelLeaseController::class, 'index'])->name('cancel_lease.index');
Route::get('/cancel_lease/show/{id}', [CancelLeaseController::class, 'show'])->name('cancel_lease.show');


Route::get('/invoices' , [InvoiceController::class, 'index'])->name('invoices.index');
Route::get('/invoices/create' , [InvoiceController::class, 'create'])->name('invoices.create');
Route::post('/invoices/store' , [InvoiceController::class, 'store'])->name('invoices.store');
Route::get('/invoices/show/{invoice}', [InvoiceController::class, 'show'])->name('invoices.show');
Route::patch('invoices/{invoice}/cancel', [InvoiceController::class, 'cancel'])->name('invoices.cancel');
Route::get('invoices/fetch-lease/{lease}', [InvoiceController::class, 'fetchLease']) ->name('invoices.fetch-lease');
Route::post('/invoices/{invoice}/notify', [InvoiceController::class, 'notify'])->name('invoices.notify');


    
Route::get('/payments', [PaymentController::class, 'index'])->name('payments.index');
Route::get('/payments/{payment}', [PaymentController::class, 'show'])->name('payments.show');
Route::post('/payments/{payment}/status', [PaymentController::class, 'updateStatus'])->name('payments.updateStatus');


});