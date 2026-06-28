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

        // Bust the CMS content cache whenever any home-content record changes,
        // so admin edits reflect on the public site immediately.
        $flushCmsCache = function () {
            foreach (['cms.slides', 'cms.services', 'cms.stats', 'cms.facilities', 'temple.primary'] as $key) {
                cache()->forget($key);
            }
        };

        \App\Models\HomeSlide::saved($flushCmsCache);
        \App\Models\HomeSlide::deleted($flushCmsCache);
        \App\Models\HomeService::saved($flushCmsCache);
        \App\Models\HomeService::deleted($flushCmsCache);
        \App\Models\HomeStat::saved($flushCmsCache);
        \App\Models\HomeStat::deleted($flushCmsCache);
        \App\Models\Facility::saved($flushCmsCache);
        \App\Models\Facility::deleted($flushCmsCache);
        \App\Models\Temple::saved($flushCmsCache);
        \App\Models\Temple::deleted($flushCmsCache);
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
