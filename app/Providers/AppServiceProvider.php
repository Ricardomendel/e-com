<?php

namespace App\Providers;

use App\Filament\Resources\BankingResource;
use App\Filament\Resources\CategoryResource;
use App\Filament\Resources\ProfileResource;
use Carbon\Carbon;
use Filament\Facades\Filament;
use Filament\Navigation\UserMenuItem;
use Filament\Navigation\NavigationItem;
use Illuminate\Support\ServiceProvider;
use App\Filament\Resources\UserResource;
use Filament\Navigation\NavigationBuilder;
use Filament\Navigation\NavigationGroup;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Carbon::setLocale(config('app.locale'));

        // Force HTTPS and safely set root URL only if APP_URL is valid
        if (config('app.env') === 'production') {
            \URL::forceScheme('https');

            $rootUrl = rtrim((string) config('app.url'), '/');
            $parts = parse_url($rootUrl) ?: [];
            $scheme = $parts['scheme'] ?? null;
            $host = $parts['host'] ?? null;
            if ($scheme && $host) {
                \URL::forceRootUrl($scheme.'://'.$host);
            }

            $assetUrl = (string) config('app.asset_url');
            if ($assetUrl !== '') {
                $assetParts = parse_url($assetUrl) ?: [];
                if (!isset($assetParts['scheme'], $assetParts['host'])) {
                    config(['app.asset_url' => null]);
                } else {
                    config(['app.asset_url' => rtrim($assetParts['scheme'].'://'.$assetParts['host'], '/')]);
                }
            }

            if (empty(config('livewire.asset_url'))) {
                $effective = config('app.asset_url') ?: ($scheme && $host ? $scheme.'://'.$host : null);
                if ($effective) {
                    config(['livewire.asset_url' => $effective]);
                }
            }
        }

        Filament::serving(function () {
            // Ensure Filament picks the correct base when serving assets
            if (config('app.asset_url') && empty(config('livewire.asset_url'))) {
                config(['livewire.asset_url' => config('app.asset_url')]);
            }
            Filament::registerNavigationGroups([
                'Admin Management',
                'Staff Management',
            ]);

            Filament::registerUserMenuItems([
                'account' => UserMenuItem::make()
                    ->label('Profile')
                    ->url(ProfileResource::getUrl()),
            ]);
        });
    }
}
