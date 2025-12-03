<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Illuminate\Support\Facades\Auth;

class Notifications extends Page
{
    public function getView(): string
    {
        return 'filament.pages.notifications';
    }

    public static function getNavigationIcon(): string
    {
        return 'heroicon-o-bell';
    }

    protected static ?string $navigationLabel = 'Notifications';

    protected static ?int $navigationSort = 100;

    protected static ?string $navigationBadge = null;

    public static function getNavigationBadge(): ?string
    {
        return Auth::user()?->unreadNotifications()->count() ?: null;
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'danger';
    }

    public function getNotifications()
    {
        return Auth::user()->notifications()->paginate(20);
    }

    public function markAsRead($notificationId)
    {
        $notification = Auth::user()->notifications()->find($notificationId);
        if ($notification) {
            $notification->markAsRead();
        }

        $this->redirect(request()->header('Referer'));
    }

    public function markAllAsRead()
    {
        Auth::user()->unreadNotifications->markAsRead();

        $this->redirect(request()->header('Referer'));
    }
}