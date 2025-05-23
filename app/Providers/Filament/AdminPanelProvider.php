<?php

namespace App\Providers\Filament;

use App\Filament\Widgets\LatestOrdersTable;
use App\Filament\Widgets\MonthlySalesChart;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use App\Filament\Widgets\QuickStats;
use App\Filament\Widgets\SalesByCategoryChart;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Filament\Notifications\Livewire\Notifications;
use Illuminate\Session\Middleware\AuthenticateSession;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->brandLogo(asset('img/logo.png'))
            ->brandLogoHeight('64px') 
            ->default()
            ->id('admin')
            ->path('admin')
            ->login()
            ->colors([
                'primary' =>'#3b82f6', // Warna utama panel
            ])
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources') // Mengubah lokasi Resources Admin
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages') // Mengubah lokasi Pages Admin
            ->pages([
                Pages\Dashboard::class, // Halaman Dashboard untuk Admin
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets') // Mengubah lokasi Widgets Admin
            ->widgets([
                QuickStats::class,
                MonthlySalesChart::class,
                LatestOrdersTable::class,
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
            ->databaseNotifications()
            ->databaseNotificationsPolling('10s') 
            ->globalSearch(false)
            ->authMiddleware([
                Authenticate::class,
            ]);
    }
}

