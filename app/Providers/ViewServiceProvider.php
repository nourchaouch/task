<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;

class ViewServiceProvider extends ServiceProvider
{
    public function register()
    {
        //
    }

    public function boot()
    {
        View::composer('*', function ($view) {
            $notificationCount = 0;
            if (Auth::check()) {
                $notificationCount = Auth::user()->unreadNotifications()->count() ?? 0;
            }
            $view->with('notificationCount', $notificationCount);
        });
    }
}
