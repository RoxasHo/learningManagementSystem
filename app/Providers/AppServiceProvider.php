<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Auth;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }
    // 检查超级用户权限的方法
    protected function checkSuperuser()
    {
        if (Auth::check() && Auth::user()->role !== 'Superuser') {
            abort(403, 'Unauthorized action.');
        }
    }
    


    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Paginator::useBootstrapFive();
        require_once base_path('app/Helpers/DateHelper.php');
    }
}
