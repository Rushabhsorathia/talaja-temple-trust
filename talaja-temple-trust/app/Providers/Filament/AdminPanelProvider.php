<?php

namespace App\Providers\Filament;

use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Navigation\NavigationGroup;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Support\Enums\MaxWidth;
use Filament\Widgets;
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
            ->login()
            ->darkMode(false)
            ->brandName('Talaja Temple Trust')
            ->favicon(asset('favicon.ico'))
            ->viteTheme('resources/css/filament/admin/theme.css')
            ->colors([
                // Brand saffron as primary (exact site palette).
                'primary' => [
                    50 => '#fff8ed',
                    100 => '#ffefd4',
                    200 => '#ffdba8',
                    300 => '#ffc070',
                    400 => '#ff9a37',
                    500 => '#ff7d10',
                    600 => '#f06106',
                    700 => '#c74808',
                    800 => '#9e390e',
                    900 => '#7f310f',
                    950 => '#451605',
                ],
                'gray' => Color::Stone,      // warm neutral to pair with cream/saffron
                'danger' => Color::Rose,
                'info' => Color::Blue,
                'success' => Color::Emerald,
                'warning' => Color::Amber,
            ])
            ->font('Inter')
            ->sidebarCollapsibleOnDesktop()
            ->maxContentWidth(MaxWidth::ScreenTwoExtraLarge)
            ->navigationGroups([
                NavigationGroup::make('Donations')
                    ->icon('heroicon-o-banknotes'),
                NavigationGroup::make('Finance')
                    ->icon('heroicon-o-building-library'),
                NavigationGroup::make('Accommodation')
                    ->icon('heroicon-o-home-modern'),
                NavigationGroup::make('Shop')
                    ->icon('heroicon-o-shopping-bag'),
                NavigationGroup::make('Content')
                    ->icon('heroicon-o-newspaper'),
                NavigationGroup::make('Communication')
                    ->icon('heroicon-o-chat-bubble-left-right'),
                NavigationGroup::make('Configuration')
                    ->icon('heroicon-o-cog-6-tooth')
                    ->collapsed(),
                NavigationGroup::make('Reports')
                    ->icon('heroicon-o-chart-bar'),
            ])
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                Pages\Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([
                Widgets\AccountWidget::class,
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
            ]);
    }
}
