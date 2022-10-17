<?php

namespace App\Http\Controllers\ctl;

use App\Http\Controllers\ctl\_commonController;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

class BrPartnerSiteController extends _commonController
{
    /**
     * TODO: phpdoc
     */
    public function index(Request $request)
    {
        return redirect()->route('ctl.brPartnerSite.search');
    }

    /**
     * TODO: phpdoc
     */
    public function search(Request $request)
    {
        return 'hello search controller';
    }
}