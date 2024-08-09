<?php

namespace Teamnovu\Localize\Services;

use Illuminate\Support\Facades\File;

class LangFileService
{
    public static function path(string $site): string
    {
        return base_path(config('localize.folder')."/{$site}.json");
    }

    public static function get(string $site): array
    {
        $path = LangFileService::path($site);
        if (! File::exists($path)) {
            return [];
        }

        return json_decode(File::get($path), true);
    }

    public static function put(string $site, array $content): void
    {
        $json = json_encode(
            $content,
            JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES
        );

        File::put(LangFileService::path($site), $json);
    }
}
