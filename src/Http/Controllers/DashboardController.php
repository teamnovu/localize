<?php

namespace Teamnovu\Localize\Http\Controllers;

use Statamic\Facades\Site;
use Illuminate\Support\Facades\File;

class DashboardController
{
    public function index()
    {
        $site = Site::current()->handle();
        $filePath = base_path("content/localize/{$site}.json");

        $translations = File::get($filePath);

        return view('localize::dashboard', [
            'site' => $site,
            'translations' => $translations,
        ]);
    }

    public function update()
    {
        $site = request()->get('site');
        $filePath = base_path("content/localize/{$site}.json");

        $updated = json_encode(
            request()->get('translations'),
            JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES
        );

        File::put($filePath, $updated);

        return response()->json([
            'status' => 'updated',
        ]);

    }
}
