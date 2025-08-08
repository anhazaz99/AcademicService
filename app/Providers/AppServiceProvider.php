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
        // Đăng ký route API trong bootstrap (Laravel 12 đã hỗ trợ trong bootstrap/app.php)
        // Không cần cấu hình thêm tại đây.
    }
}
