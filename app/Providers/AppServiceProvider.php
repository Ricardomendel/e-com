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

        // Force HTTPS URLs when behind a proxy (Render)
        if (config('app.env') === 'production') {
            \URL::forceScheme('https');

            // Normalize APP_URL and ASSET_URL to avoid malformed links like "/https:/path"
            $rootUrl = config('app.url');
            if (is_string($rootUrl) && $rootUrl !== '') {
                $rootUrl = preg_replace('#^https:/([^/])#i', 'https://$1', $rootUrl);
                $rootUrl = preg_replace('#^http:/([^/])#i', 'http://$1', $rootUrl);
                $rootUrl = rtrim($rootUrl, '/');
                \URL::forceRootUrl($rootUrl);
            }

            $assetUrl = config('app.asset_url');
            if (is_string($assetUrl) && $assetUrl !== '') {
                $assetUrl = preg_replace('#^https:/([^/])#i', 'https://$1', $assetUrl);
                $assetUrl = preg_replace('#^http:/([^/])#i', 'http://$1', $assetUrl);
                $assetUrl = rtrim($assetUrl, '/');
                if (!preg_match('#^https?://#i', $assetUrl)) {
                    // Ignore invalid ASSET_URL to prevent broken paths
                    $assetUrl = null;
                }
                config(['app.asset_url' => $assetUrl]);
            }
        }

        Filament::serving(function () {
            // Ensure Livewire/Filament assets use correct base URL
            if (config('app.asset_url')) {
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
