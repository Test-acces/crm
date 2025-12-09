<?php

namespace App\Providers\Filament;

use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Panel;
use Filament\PanelProvider;
use App\Filament\Pages\Dashboard;
use Filament\Support\Colors\Color;
use Filament\Navigation\MenuItem;
use Filament\Navigation\NavigationItem;
use Illuminate\Contracts\View\View;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->brandName('CRM Léger')
            ->brandLogo(asset('favicon.svg'))
            ->brandLogoHeight('2rem')
            ->login()
            ->colors([
                'primary' => Color::Blue,
                'secondary' => Color::Gray,
            ])
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\Filament\Resources')
            ->pages([
                Dashboard::class,
            ])
            ->widgets([
                // Only register widgets that are actually used
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ])
            ->renderHook(
                'panels::head.start',
                fn (): View => view('filament.hooks.head')
            )
            ->databaseNotifications()
            ->databaseNotificationsPolling('30s')
            ->userMenuItems([
                MenuItem::make()
                    ->label('Role: ' . (auth()->user()?->role?->label() ?? 'Administration'))
                    ->icon(auth()->user()?->role?->icon() ?? 'heroicon-o-question-mark-circle'),
                MenuItem::make()
                    ->label('Profile')
                    ->url(fn () => '/admin/profile')
                    ->icon('heroicon-o-user'),
                MenuItem::make()
                    ->label('Settings')
                    ->url(fn () => '/admin/settings')
                    ->icon('heroicon-o-cog')
                    ->visible(fn () => auth()->user()?->canAccessSettings() ?? false),
            ]);
    }
}
