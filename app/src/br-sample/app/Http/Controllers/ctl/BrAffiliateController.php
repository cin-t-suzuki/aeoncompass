<?php

namespace App\Http\Controllers\ctl;

use App\Common\DateUtil;
use App\Http\Controllers\ctl\_commonController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Exception;
use Illuminate\Support\Facades\Log;
use App\Common\Traits;
use App\Models\Affiliate;
use App\Util\Models_Cipher;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Http;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Carbon\Carbon;

class BrAffiliateController extends _commonController
{
    use Traits;

    /**
     * 一覧表示
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function list(Request $request)
    {
        $a_params = $request->all();

        //オブジェクト取得
        $affiliateModel = new Affiliate();

        //一覧の取得
        $a_affiliate_list = $affiliateModel->getAffiliaterLists();

        // ビューを表示
        return view('ctl.brAffiliate.list', [
            'params'         => $a_params,
            'affiliate_list'      => $a_affiliate_list,
        ]);
    }
}
