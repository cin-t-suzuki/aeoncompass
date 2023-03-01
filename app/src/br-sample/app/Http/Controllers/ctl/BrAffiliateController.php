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
use App\Models\Affiliater;
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

    /**
     * 詳細表示
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function details(Request $request)
    {
        // $a_params = $this->_request->getParams();

        // Affiliate モデル の インスタンスを取得
        $affiliaterModel = new Affiliater();

        //オブジェクト取得
        $affiliateModel = new Affiliate();

        //一覧の取得
        $a_conditions = [
            'affiliater_cd' => $request->input('affiliater_cd')
        ];

        $a_affiliate_list = $affiliateModel->getAffiliatePrograms($a_conditions);
        $a_conv_list = [];
        // $o_cipher = new Br_Models_Cipher((string)$this->box->config->environment->cipher->public->key);
        $cipher = new Models_Cipher(config('settings.cipher_key')); //書き換えあっている？
        foreach ($a_affiliate_list as $key1 => $list) {
            foreach ($list as $key2 => $value) {
                $value['password']  = $cipher->decrypt($value['password']);
                $a_conv_list[$key2] = $value;
            }
        }
        unset($a_affiliate_list);
        $a_affiliate_list['values'] = $a_conv_list;
        $a_row = $affiliaterModel->selectByKey($request->input('affiliater_cd')); //find→selectByKeyでいいか？

        // ビューを表示
        return view('ctl.brAffiliate.details', [
            'affiliate'      => $a_affiliate_list,
            'affiliater_value'      => $a_row,
        ]);
    }
}
