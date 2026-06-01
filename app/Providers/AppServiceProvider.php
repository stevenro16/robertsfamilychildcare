<?php

namespace App\Providers;

use App\Models\Message;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void {}

    public function boot(): void
    {
        View::composer('layouts.portal', function ($view) {
            $unread = 0;
            if (Auth::check()) {
                $unread = Message::whereNull('readAt')->where('senderRole', 'PARENT')->count();
            }
            $view->with('unread', $unread);
        });
    }
}
