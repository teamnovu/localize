<?php

namespace Teamnovu\Localize\Http\Controllers;

use Illuminate\Routing\Controller;

class PublicController extends Controller
{
    public function serve()
    {
        $site = \Request::segment(3);
        $filePath = base_path(config('localize.folder')."/{$site}.json");

        return response()->file($filePath);
    }
}
