<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use App\Models\Pendaftaran;

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
        // Set pagination view to bootstrap-5
        \Illuminate\Pagination\Paginator::defaultView('vendor.pagination.bootstrap-5');
        \Illuminate\Pagination\Paginator::defaultSimpleView('vendor.pagination.simple-bootstrap-5');

        // Share pending persetujuan asesmen count to all views
        View::composer('layouts.app', function ($view) {
            if (Auth::check() && Auth::user()->role === 'admin') {
                $pendingPersetujuanCount = Pendaftaran::where('status', 'in_progress')
                    ->whereNotNull('asesmen_data')
                    ->count();
                
                $view->with('pendingPersetujuanCount', $pendingPersetujuanCount);
            } else {
                $view->with('pendingPersetujuanCount', 0);
            }
        });
    }
}
