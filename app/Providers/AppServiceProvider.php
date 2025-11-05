<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use App\Models\Pendaftaran;
use App\Models\ObservasiChecklist;
use App\Models\PenyesuaianChecklist;

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
            if (Auth::check()) {
                $user = Auth::user();
                
                if ($user->role === 'admin') {
                    $pendingPersetujuanCount = Pendaftaran::where('status', 'in_progress')
                        ->whereNotNull('asesmen_data')
                        ->count();
                    
                    $view->with('pendingPersetujuanCount', $pendingPersetujuanCount);
                } else {
                    $view->with('pendingPersetujuanCount', 0);
                }

                // Share pending observasi checklist count for mahasiswa
                if ($user->role === 'mahasiswa') {
                    $pendingObservasiCount = ObservasiChecklist::whereHas('pendaftaran', function($query) use ($user) {
                            $query->where('user_id', $user->id);
                        })
                        ->whereNull('mahasiswa_signature')
                        ->count();
                    
                    $view->with('pendingObservasiCount', $pendingObservasiCount);

                    // Share pending penyesuaian checklist count for mahasiswa
                    $pendingPenyesuaianCount = PenyesuaianChecklist::whereHas('pendaftaran', function($query) use ($user) {
                            $query->where('user_id', $user->id);
                        })
                        ->whereNull('mahasiswa_signature')
                        ->count();
                    
                    $view->with('pendingPenyesuaianCount', $pendingPenyesuaianCount);
                } else {
                    $view->with('pendingObservasiCount', 0);
                    $view->with('pendingPenyesuaianCount', 0);
                }
            } else {
                $view->with('pendingPersetujuanCount', 0);
                $view->with('pendingObservasiCount', 0);
                $view->with('pendingPenyesuaianCount', 0);
            }
        });
    }
}
