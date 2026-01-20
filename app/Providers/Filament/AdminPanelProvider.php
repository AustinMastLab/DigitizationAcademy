<?php

namespace App\Providers\Filament;

use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Navigation\NavigationItem;
use Filament\Pages\Dashboard;
use Filament\Panel;
use Filament\PanelProvider;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Spatie\ResponseCache\Middlewares\DoNotCacheResponse;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('admin')
            ->path('admin')
            ->brandName('Digitization Academy Admin')
            ->colors([
                'primary' => [
                    50 => '240, 241, 254',   // Very light blue
                    100 => '224, 231, 253',  // Light blue
                    200 => '199, 210, 252',  // Lighter blue
                    300 => '165, 180, 251',  // Light blue
                    400 => '129, 140, 248',  // Medium-light blue
                    500 => '99, 102, 241',   // Medium blue
                    600 => '79, 70, 229',    // Primary blue
                    700 => '67, 56, 202',    // Dark blue
                    800 => '55, 48, 163',    // Darker blue
                    900 => '49, 46, 129',    // Very dark blue
                    950 => '0, 2, 72',       // Darkest blue (#000248)
                ],
                'gray' => [
                    50 => '240, 242, 245',   // Darker light gray (main background)
                    100 => '228, 232, 237',  // Darker gray (widget backgrounds)
                    200 => '215, 220, 227',  // Medium-light gray
                    300 => '185, 194, 205',  // Medium gray
                    400 => '135, 149, 165',  // Medium-dark gray
                    500 => '90, 105, 125',   // Dark gray
                    600 => '65, 78, 95',     // Darker gray
                    700 => '45, 58, 75',     // Very dark gray
                    800 => '28, 38, 52',     // Almost black
                    900 => '15, 23, 35',     // Very dark
                    950 => '5, 10, 18',      // Darkest
                ],
            ])
            ->darkMode(false)
            ->discoverResources(in: app_path('Filament/Admin/Resources'), for: 'App\Filament\Admin\Resources')
            ->discoverPages(in: app_path('Filament/Admin/Pages'), for: 'App\Filament\Admin\Pages')
            ->pages([
                Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Admin/Widgets'), for: 'App\Filament\Admin\Widgets')
            ->widgets([
                \App\Filament\Admin\Widgets\UpcomingEventsWidget::class,
            ])
            ->navigationItems([
                NavigationItem::make('Website')
                    ->url('/')
                    ->icon('heroicon-o-window')
                    ->sort(-3),
                NavigationItem::make('Horizon')
                    ->url('/horizon')
                    ->icon('heroicon-o-chart-bar')
                    ->openUrlInNewTab()
                    ->sort(-2),
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
                DoNotCacheResponse::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ])
            ->authGuard('web');  // Use your default guard
    }
}
