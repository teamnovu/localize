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

        $data = json_decode(File::get($path), true);

        if (! is_array($data)) {
            return [];
        }

        return self::sanitizeTranslationTree($data);
    }

    /**
     * Recursively coerce numeric leaves to strings (translation content is string-only).
     */
    private static function sanitizeTranslationTree(array $tree): array
    {
        $out = [];

        foreach ($tree as $key => $value) {
            if (is_array($value)) {
                $out[$key] = self::sanitizeTranslationTree($value);
            } elseif (is_int($value) || is_float($value)) {
                $out[$key] = (string) $value;
            } else {
                $out[$key] = $value;
            }
        }

        return $out;
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
