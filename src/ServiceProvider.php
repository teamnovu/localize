<?php

namespace Teamnovu\Localize;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Route;
use Statamic\Facades\CP\Nav;
use Statamic\Facades\Permission;
use Statamic\Facades\Site;
use Statamic\Facades\User;
use Statamic\Providers\AddonServiceProvider;
use Statamic\Statamic;
use Teamnovu\Localize\Http\Controllers\PublicController;

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

        $this->bootAddonPermissions();
        $this->registerRoutes();
        $this->bootAddonNav();

    }

    protected function ensureFiles()
    {
        Site::all()->each(function ($site) {
            $filePath = base_path("content/localize/{$site->handle()}.json");
            File::ensureDirectoryExists(dirname($filePath));
            if (! File::exists($filePath)) {
                File::put($filePath, '{}');
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
            $user = User::current();
            if ($user->can('edit localize')) {
                $nav->content('Localize')
                    ->route('localize.dashboard')
                    ->icon('earth');
            }
        });
    }

    protected function registerRoutes()
    {
        Route::prefix('api/localize')->group(function () {
            Site::all()->each(fn ($site) => Route::get(
                $site->handle(),
                [PublicController::class, 'serve']
            ));
        });
    }
}
