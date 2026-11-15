<?php

namespace App\Providers;

use App\Models\ContactMessage;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Illuminate\View\View as ViewInstance;

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
        View::composer('partials.header', function (ViewInstance $view): void {
            $user = Auth::user();
            if (! $user) {
                return;
            }

            $unreadMessageCount = $user->is_admin
                ? ContactMessage::where('is_read', false)->count()
                : 0;
            $pendingCount = $user->is_admin
                ? User::where('is_approved', false)->count()
                : 0;

            $view->with([
                'unreadMessageCount' => $unreadMessageCount,
                'pendingCount' => $pendingCount,
                'notificationCount' => $unreadMessageCount + $pendingCount,
            ]);
        });
    }
}
