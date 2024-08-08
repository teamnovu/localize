<?php

namespace Teamnovu\Localize\Http\Controllers;

use Statamic\Facades\Site;
use Illuminate\Support\Facades\File;
use Illuminate\Http\Request;

class DashboardController
{
    public function index()
    {
        return view('localize::dashboard', [
            'site' => Site::selected()->handle(),
            'sites' => $this->getSites(),
        ]);
    }

    public function update(Request $request)
    {
        Site::all()->each(function (string $key) use ($request) {

            $translation = $request->input('translations')[$key];

            if (empty($translation)) {
                return;
            }

            $filePath = base_path(config('localize.folder')."/{$key}.json");
            $original = json_decode(File::get($filePath), true);

            $updated = array_replace_recursive($original, $translation);

            $updatedJson = json_encode(
                $updated,
                JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES
            );
            File::put($filePath, $updatedJson);
        });

        return response()->json([
            'status' => __('Saved'),
            'sites' => $this->getSites(),
        ]);
    }

    private function getSites()
    {
        return Site::all()->map(fn ($site) => [
            'handle' => $site->handle(),
            'name' => $site->name(),
            'translations' => json_decode(
                File::get(base_path(config('localize.folder')."/{$site->handle()}.json")),
                true
            ),
        ]);
    }
}
