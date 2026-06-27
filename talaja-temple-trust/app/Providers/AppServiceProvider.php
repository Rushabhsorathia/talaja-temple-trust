<?php

namespace App\Providers;

use Filament\Support\Enums\MaxWidth;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Vite;
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
        Vite::prefetch(concurrency: 3);

        $this->app->setLocale(session('locale', config('app.locale', 'en')));

        $this->configureFilamentDefaults();
    }

    /**
     * Global Filament UI defaults so every resource table shares a
     * consistent look & behaviour without per-resource boilerplate.
     */
    protected function configureFilamentDefaults(): void
    {
        Table::configureUsing(function (Table $table): void {
            $table
                ->striped()
                ->defaultPaginationPageOption(25)
                ->paginationPageOptions([10, 25, 50, 100])
                ->filtersLayout(FiltersLayout::Dropdown)
                ->filtersFormWidth(MaxWidth::Medium)
                ->deferLoading()
                ->persistSearchInSession()
                ->persistColumnSearchesInSession()
                ->persistSortInSession()
                ->persistFiltersInSession();
        });
    }
}
