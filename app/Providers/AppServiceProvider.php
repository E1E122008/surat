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

    public function boot(): void
    {
        if (\Illuminate\Support\Facades\Schema::hasTable('system_settings')) {
            $systemSetting = \App\Models\SystemSetting::first();
            \Illuminate\Support\Facades\View::share('systemSetting', $systemSetting);
        }
    }
}
