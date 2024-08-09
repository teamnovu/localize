<?php

namespace Teamnovu\Localize\Http\Controllers;

use Statamic\Facades\Site;
use Illuminate\Http\Request;
use Teamnovu\Localize\Services\LangFileService;

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

            $translation = $request->input('translations');

            if (empty($translation) || empty($translation[$key])) {
                return;
            }

            $original = LangFileService::get($key);

            $updated = array_replace_recursive($original, $translation[$key]);

            LangFileService::put($key, $updated);
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
            'translations' => LangFileService::get($site->handle()),
        ]);
    }
}
