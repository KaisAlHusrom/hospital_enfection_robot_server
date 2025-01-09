<?php

namespace App\Providers;

use App\Interfaces\Services\IAuthService;
use App\Interfaces\Services\IUserService;
use App\Services\AuthService;
use App\Services\StyleProperties\StylePropertiesRegistry;
use App\Services\UserService;
use Illuminate\Support\ServiceProvider;
use Laravel\Sanctum\Sanctum;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(IAuthService::class, AuthService::class);
        $this->app->bind(IUserService::class, UserService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Sanctum::authenticateAccessTokensUsing(
            fn($token, $isValidToken) => $isValidToken
        );
    }
}
