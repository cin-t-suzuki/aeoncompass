<?php

namespace App\Http\Controllers\ctl;

use App\Http\Controllers\ctl\_commonController;
use App\Models\PartnerSite;
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
        // 検索ワードが request に含まれればそれを適用する
        // そうでなければ session を見て、あればそれを適用する
        if ($request->has('keywords')) {
            $keywords = $request->input('keywords');
        } else {
            $keywords = $request->session()->pull('keywords', '');
        }

        $model = new PartnerSite();
        $sites = $model->getPartnerSiteByKeywords($keywords);

        $request->session()->put('keywords', $keywords);
        return view('ctl.brPartnerSite.search', [
            'sites' => $sites,
            'keywords' => $keywords,
        ]);
    }
}