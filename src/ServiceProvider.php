<?php

namespace Teamnovu\Localize;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Route;
use Statamic\Facades\CP\Nav;
use Statamic\Facades\Permission;
use Statamic\Facades\Site;
use Statamic\Providers\AddonServiceProvider;
use Statamic\Statamic;
use Teamnovu\Localize\Http\Controllers\PublicController;
use Teamnovu\Localize\Services\LangFileService;

class ServiceProvider extends AddonServiceProvider
{
    protected $scripts = [
        __DIR__.'/../resources/dist/js/cp.js',
    ];

    protected $stylesheets = [
        __DIR__.'/../resources/dist/css/cp.css',
    ];

    protected $routes = [
        'cp' => __DIR__.'/../routes/cp.php',
    ];

    public function bootAddon()
    {
        Statamic::afterInstalled(function () {
            $this->ensureFiles();
        });

        $this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'localize');

        $this->publishes([
            __DIR__.'/../config/localize.php' => config_path('localize.php'),
        ], 'localize-config');

        $this->bootAddonPermissions();
        $this->bootAddonNav();
        $this->registerRoutes();

    }

    protected function ensureFiles()
    {
        Site::all()->each(function ($site) {
            $path = LangFileService::path($site->handle());
            File::ensureDirectoryExists(dirname($path));
            if (! File::exists($path)) {
                File::put($path, '{}');
            }
        });
    }

    protected function bootAddonPermissions()
    {
        Permission::group('localize', 'Localize', function () {
            Permission::register('edit localize')->label('See and Edit');
        });
    }

    protected function bootAddonNav()
    {
        Nav::extend(function ($nav) {
            $nav->content('Localize')
                ->route('localize.dashboard')
                ->icon('partial')
                ->can('edit localize');
        });
    }

    protected function registerRoutes()
    {

        if (config('localize.api_disabled')) {
            return false;
        }

        if (! config('statamic.api.enabled') && ! config('statamic.graphql.enabled')) {
            return false;
        }

        Route::prefix(config('localize.api_route'))->group(function () {
            Site::all()->each(fn ($site) => Route::get(
                $site->handle(),
                [PublicController::class, 'serve']
            ));
        });
    }
}
