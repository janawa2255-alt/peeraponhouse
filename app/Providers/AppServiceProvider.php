<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Share badge counts with sidebar
        view()->composer('layouts.sidebar', function ($view) {
            $pendingPaymentsCount = \App\Models\Payment::where('status', 0)->count();
            $cancelledLeasesCount = \App\Models\CancelLease::where('status', 0)->count(); // รออนุมัติ
            
            $view->with([
                'pendingPaymentsCount' => $pendingPaymentsCount,
                'cancelledLeasesCount' => $cancelledLeasesCount,
            ]);
        });
    }
}
