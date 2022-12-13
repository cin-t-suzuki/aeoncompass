<?php

namespace App\Http\Controllers\ctl;

use App\Common\DateUtil;
use App\Http\Controllers\ctl\_commonController;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\DB;
use Exception;
use Illuminate\Support\Facades\Log;
use App\Common\Traits;
use App\Models\BillPayPtn;
use App\Util\Models_Cipher;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Http;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Carbon\Carbon;

class BrBillPayPtnController extends _commonController
{
    use Traits;

    //======================================================================
    // パートナー精算書一覧表示
    //======================================================================

    public function list()
    {
        // データを取得
        $requestBrBillPayPtn = Request::all();
        $year = $requestBrBillPayPtn['year'] ?? null;
        $month = $requestBrBillPayPtn['month'] ?? null;

        $BillPayPtnModel = new BillPayPtn();

        // オブジェクトの取得
        $o_models_date = new DateUtil();

        if ($this->is_empty($year)) {
            $year = $o_models_date->to_format('Y');
        }
        if ($this->is_empty($month)) {
            $month = $o_models_date->to_format('m');
        }

        $o_billpay_ym = new DateUtil($year . '-' . $month . '-01');
        $billpayptn = $BillPayPtnModel->getBillPayPtn(['billpay_ym' => $o_billpay_ym->to_format('Y-m')]);

        if ($billpayptn == 'NotFound') {
            $this->addGuideMessage("精算書が作成されたパートナーはありませんでした。");
            // データを ビューにセット
            $this->addViewData("billpayptn", []);
        } else {
            $this->addViewData("billpayptn", $billpayptn);
        }

        $this->addViewData("year", $year);
        $this->addViewData("month", $month);

        // ビューを表示
        return view("ctl.brbillpayptn.list", $this->getViewData());
    }

    //======================================================================
    // 精算内容を取得する
    //======================================================================
    // aa_form_set_params 検索用パラメータ
    //
    public function customer()
    {
        // データを取得
        $requestBrBillPayPtn = Request::all();

        //初期化
        $BillPayPtnModel = new BillPayPtn();
        $a_conditions     = [];
        $error = '';

        $a_conditions['billpay_ym'] =  $requestBrBillPayPtn['billpay_ym'];
        $a_conditions['customer_id'] = $requestBrBillPayPtn['customer_id'];

        $a_customer = $BillPayPtnModel->getBillPayPtn($a_conditions);
        $a_customer = $a_customer[0];

        // 指定月の精算状況
        $a_conditions['billpay_ptn_cd']  = $a_customer['billpay_ptn_cd'];

        //月単位の精算状況(元ソースのNTA分は削除)
        $a_book = $BillPayPtnModel->getBook($a_conditions);

        // 検索結果が0件のときはメッセージを表示
        if (count($a_book) < 1) {
            if (date('Y-m-d') < ($requestBrBillPayPtn['billpay_ym'] ?? date('Y-m')) . '-' . sprintf('%02d', $a_customer['billpay_day'])) {
                $this->addErrorMessage("ご指定の精算月は、精算実施前のためご確認いただけません。精算締日後にご確認ください。");
            } else {
                $this->addGuideMessage("ご指定の精算月は、精算対象となる予約がありませんでしたので精算はありません。");
            }
        }

        $a_sites = [];

        if ($requestBrBillPayPtn['customer_id'] == 1) {
            // 在庫属性・サイト単位でグループし、テンプレート上で合計行の制御しないでいいようにする。
            $a_sites = ['1' => [], '2' => [], '3' => []];
            for ($n_cnt = 0; $n_cnt < count($a_book); $n_cnt++) {
                $a_sites[$a_book[$n_cnt]['stock_type']][$a_book[$n_cnt]['site_cd']][] = $a_book[$n_cnt];
            }
        } else {
            // サイト単位でグループし、テンプレート上で合計行の制御しないでいいようにする。
            for ($n_cnt = 0; $n_cnt < count($a_book); $n_cnt++) {
                $a_sites[$a_book[$n_cnt]['site_cd']][] = $a_book[$n_cnt];
            }
        }
        // 検索パラメータの設定
        $_a_pager_params = $BillPayPtnModel->setSearchParams($requestBrBillPayPtn);

        if ($error == 'NotFound') {
            $this->addGuideMessage("精算書が作成されたパートナーはありませんでした。");
        }

        // データを ビューにセット
        $this->addViewData("form_params", $requestBrBillPayPtn);
        // $this->_assign->partner_cd              = $this->_s_partner_cd;//いる？？
        $this->addViewData("customer", $a_customer);
        $this->addViewData("book", $a_sites);
        $this->addViewData("search_params", $_a_pager_params);

        // ビューを表示
        return view("ctl.brbillpayptn.customer", $this->getViewData());
    }
    //======================================================================
    // 明細表示
    //======================================================================
    protected function detail()
    {
        // データを取得
        $requestBrBillPayPtn = Request::all();

        //初期化
        $BillPayPtnModel = new BillPayPtn();
        $a_conditions     = [];
        $error = '';

        // ページ数設定
        $requestBrBillPayPtn['page'] = $requestBrBillPayPtn['page'] ?? 1;

        $a_conditions['billpay_ym'] =  $requestBrBillPayPtn['billpay_ym'];
        $a_conditions['customer_id'] = $requestBrBillPayPtn['customer_id'];
        $a_conditions['site_cd']         = $requestBrBillPayPtn['site_cd'];

        $a_customer = $BillPayPtnModel->getSite($a_conditions);

        // 指定月の精算状況
        $a_conditions['billpay_ym']      = $requestBrBillPayPtn['target_ym'] ?? null; //??null追記
        $a_conditions['billpay_ptn_cd']  = $requestBrBillPayPtn['billpay_ptn_cd'] ?? null; //??null追記
        $a_conditions['customer_id']     = $requestBrBillPayPtn['customer_id'] ?? null; //??null追記
        $a_conditions['stock_type']      = $requestBrBillPayPtn['stock_type'] ?? null; //??null追記
        $a_conditions['rate']            = $requestBrBillPayPtn['rate'] ?? null; //??null追記
        $a_conditions['msd_rate']        = ($requestBrBillPayPtn['msd_rate'] ?? 0);

        // 通常・赤伝集出設定
        $a_options['billpay']   = ($requestBrBillPayPtn['billpay'] ?? 0);
        $a_options['billpayed'] = ($requestBrBillPayPtn['billpayed'] ?? 0);

        $a_offset =  ['page' => ($requestBrBillPayPtn['page'] ?? 1), 'size' => 50];

        $a_detail = $BillPayPtnModel->getDetail($a_conditions, $a_options, $a_offset);

        // 検索結果が0件のときはメッセージを表示
        if (count($a_detail['values']) < 1) {
            $this->addErrorMessage('精算対象となる予約はありませんでした。');
        }
        // // ページャー設定
        // $a_page = array();
        // for($n_cnt = 0; $n_cnt < $a_detail['values'][0]['total_count']; $n_cnt++){
        //     $a_page[] = $n_cnt;
        // }
        // $o_paginator = Zend_Paginator::factory($a_page); // 総数を設定
        // $o_paginator->setCurrentPageNumber($this->_a_request_params['page']);             // 現在のページを設定
        // $o_paginator->setItemCountPerPage($a_offset['size']);                             // 1ページあたりの表示数を設定
        // $o_paginator->setPageRange(10);                                                      // ページ指定リンクの表示数を設定
        //
        //↓laravel仕様に書き換え（GETでパラメータ付与されてしまうが問題ないか？）
        $per_page = 10; // 1ページ当りの表示数
        // ページ番号が指定されていなかったら１ページ目
        $page_num = isset($requestBrBillPayPtn['page']) ? $requestBrBillPayPtn['page'] : 1;
        // ページ番号に従い、表示するレコードを切り出す
        $disp_rec = array_slice($a_detail['values'], ($page_num - 1) * $per_page, $per_page, true);
        // ページャーオブジェクトを生成
        $pager = new \Illuminate\Pagination\LengthAwarePaginator(
            $disp_rec, // ページ番号で指定された表示するレコード配列
            count($a_detail['values']), // 検索結果の全レコード総数
            $per_page, // 1ページ当りの表示数
            $page_num, // 表示するページ
            ['path' => ''] // ページャーのリンク先のURLを指定
        );

        // 明細パラメータの設定
        $_a_pager_params = $BillPayPtnModel->setDetailParams($requestBrBillPayPtn);

        if ($error == 'NotFound') {
            $this->addGuideMessage("精算書が作成されたパートナーはありませんでした。");
        }
        // データを ビューにセット
        $this->addViewData("form_params", $requestBrBillPayPtn);
        // $this->_assign->partner_cd              = $this->_s_partner_cd; //いる？？
        $this->addViewData("customer", $a_customer);
        $this->addViewData("detail", $a_detail);
        $this->addViewData("pager", $pager);
        $this->addViewData("search_params", $_a_pager_params);
        // ビューを表示
        return view("ctl.brbillpayptn.detail", $this->getViewData());
    }

    //======================================================================
    // 原稿を表示します。
    //======================================================================
    public function book() //初期未使用想定なので保留
    {
    }

    //======================================================================
    // 原稿を表示します。
    //======================================================================
    public function bookcsv() //初期未使用想定なので保留
    {
    }

    //======================================================================
    // CSVダウンロード
    //======================================================================
    public function csv(\Illuminate\Http\Request $request)
    {
        // データを取得
        $requestBrBillPayPtn = Request::all();
        $BillPayPtnModel = new BillPayPtn();

        $requestBrBillPayPtn['page'] = 1;

        $b_status = true;
        while ($b_status) {
            //初期化
            $offset = [];
            $detail = [];
            $customer = [];

            $b_status = $BillPayPtnModel->csv($requestBrBillPayPtn);
            if ($b_status != false) {
                $offset = $b_status['offset'];
                $detail = $b_status['detail'];
                $customer = $b_status['customer'];
            }

            //CSV出力方法を元ソースから全般的に変更しているが問題ないか？
            $header = $BillPayPtnModel->setCsvHeader($customer); //renderでbladeからではなく、モデルからの取得に変更
            $data = $BillPayPtnModel->setCsvData($customer, $offset, $detail);
            $csvList = array_merge([$header], $data);


            $response = new StreamedResponse(function () use ($request, $csvList) {
                $stream = fopen('php://output', 'w');

                //　文字化け回避
                stream_filter_prepend($stream, 'convert.iconv.utf-8/cp932//TRANSLIT');

                // CSVデータ
                foreach ($csvList as $key => $value) {
                    fputcsv($stream, $value);
                }
                fclose($stream);
            });
            $response->headers->set('Content-Type', 'application/octet-stream');
            $response->headers->set('Content-Disposition', 'attachment; filename="brbillpayptn.csv"');

            return $response;

            // $this->_request->setParam('page', $this->params('page')+1);
            $requestBrBillPayPtn['page'] = $requestBrBillPayPtn['page'] + 1;
        }
    }
}
