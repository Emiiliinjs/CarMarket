<?php

namespace App\Providers;

use App\Models\AdminNotification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
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
        View::composer(['layouts.app', 'layouts.navigation', 'admin.index'], function ($view) {
            $user = Auth::user();

            if (! $user || ! $user->is_admin) {
                $view->with('adminNotifications', collect());
                $view->with('adminNotificationCount', 0);

                return;
            }

            $payload = once(function () {
                $items = AdminNotification::query()
                    ->unread()
                    ->latest()
                    ->get();

                return [
                    'items' => $items,
                    'count' => $items->count(),
                ];
            });

            $view->with('adminNotifications', $payload['items']);
            $view->with('adminNotificationCount', $payload['count']);
        });
    }
}
