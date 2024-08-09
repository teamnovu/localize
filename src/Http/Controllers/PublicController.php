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

        return response()->file($path);
    }
}
