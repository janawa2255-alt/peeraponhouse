<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Tenant\TenantLeaseController;
use App\Http\Controllers\Auth\CustomLoginController;




Route::get('/', function () {
    return view('tenant');
});




Route::get('/lease', [TenantLeaseController::class, 'showCurrent']) ->name('lease.show');


Route::get('/login', [CustomLoginController::class, 'showLoginForm'])->name('login');
Route::post('/login/tenant', [CustomLoginController::class, 'loginTenant'])->name('login.tenant');
Route::post('/login/owner', [CustomLoginController::class, 'loginOwner'])->name('login.owner');

 