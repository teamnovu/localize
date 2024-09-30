<?php

namespace Teamnovu\Localize\Http\Controllers;

use Illuminate\Routing\Controller;
use Teamnovu\Localize\Services\LangFileService;

class PublicController extends Controller
{
    public function serve()
    {
        $site = \Request::segment(3);
        $path = LangFileService::path($site);

        return response()
        ->file($path, [
            // https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Cache-Control#no-cache
            // does not mean we don't cache it, just that it has to revalidate
            'Cache-Control' => 'no-cache'
        ]);
    }
}
