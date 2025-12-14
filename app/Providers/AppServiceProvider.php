<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\Payment\PaymentInterface;
use App\Services\Payment\BankTransferMock;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Bind PaymentInterface ke BankTransferMock sebagai default
        // (Demonstrasi Dependency Injection & Strategy Pattern)
        // Implementasi dapat di-override di controller sesuai pilihan user
        $this->app->bind(PaymentInterface::class, BankTransferMock::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
