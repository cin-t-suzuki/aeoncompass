<?php

namespace App\Http\Controllers\ctl;

use App\Common\DateUtil;
use App\Http\Controllers\ctl\_commonController;
use Illuminate\Http\Request;
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

    public function list(Request $request)
    {
        // データを取得
        $year = $request->input('year');
        $month = $request->input('month');

        $BillPayPtnModel = new BillPayPtn();

        // 日付の整形
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
            $guides[] = "精算書が作成されたパートナーはありませんでした。";
            // データを ビューにセット
            $billpayptn = [];
        }

        // ビューを表示
        return view('ctl.brbillpayptn.list', [
            'year'         => $year,
            'month'      => $month,
            'billpayptn' => $billpayptn,

            //guideメッセージがない時は空の配列を返す
            'guides'    => $guides ?? []
        ]);
    }

    //======================================================================
    // 精算内容を取得する
    //======================================================================
    // aa_form_set_params 検索用パラメータ
    //
    public function customer(Request $request)
    {
        //初期化
        $BillPayPtnModel = new BillPayPtn();
        $a_conditions     = [];
        $error = '';

        $a_conditions['billpay_ym'] =  $request->input('billpay_ym');
        $a_conditions['customer_id'] = $request->input('customer_id');

        $a_customer = $BillPayPtnModel->getBillPayPtn($a_conditions);
        // NotFoundが返ってきた場合の分岐を追記
        if ($a_customer != "NotFound") {
            $a_customer = $a_customer[0];
        }

        // 指定月の精算状況
        $a_conditions['billpay_ptn_cd']  = $a_customer['billpay_ptn_cd'] ?? null;

        //月単位の精算状況
        $a_book = $BillPayPtnModel->getBook($a_conditions);

        // 検索結果が0件のときはメッセージを表示
        if (count($a_book) < 1) {
            if (date('Y-m-d') < ($request->input('billpay_ym') ?? date('Y-m')) . '-' . sprintf('%02d', $a_customer['billpay_day'])) {
                $errors[] = "ご指定の精算月は、精算実施前のためご確認いただけません。精算締日後にご確認ください。";
            } else {
                $guides[] = "ご指定の精算月は、精算対象となる予約がありませんでしたので精算はありません。";
            }
        }

        $a_sites = [];

        if ($request->input('customer_id') == 1) {
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
        $_a_pager_params = $BillPayPtnModel->setSearchParams($request->all()); //allでいいか？

        if ($error == 'NotFound') {
            $guides[] = "精算書が作成されたパートナーはありませんでした。";
        }

        // ビューを表示
        return view('ctl.brbillpayptn.customer', [
            'form_params'         => $request->all(), //allでいいか？
            // $this->_assign->partner_cd              = $this->_s_partner_cd;//いる？？
            'customer'      => $a_customer,
            'book' => $a_sites,
            'search_params' => $_a_pager_params,

            //guideメッセージがない時は空の配列を返す
            'guides'    => $guides ?? []
        ]);
    }
    //======================================================================
    // 明細表示
    //======================================================================
    protected function detail(Request $request)
    {

        //form_param設定用に取得
        $requestBrBillPayPtn = $request->all();

        //初期化
        $BillPayPtnModel = new BillPayPtn();
        $a_conditions     = [];
        $error = '';

        // ページ数設定
        $requestBrBillPayPtn['page'] = $requestBrBillPayPtn['page'] ?? 1;

        $a_conditions['billpay_ym'] =  $request->input('billpay_ym');
        $a_conditions['customer_id'] = $request->input('customer_id');
        $a_conditions['site_cd']         = $request->input('site_cd');

        $a_customer = $BillPayPtnModel->getSite($a_conditions);

        // 指定月の精算状況
        $a_conditions['billpay_ym']      = $request->input('target_ym') ?? null; //??null追記
        $a_conditions['billpay_ptn_cd']  = $request->input('billpay_ptn_cd') ?? null; //??null追記
        $a_conditions['customer_id']     = $request->input('customer_id') ?? null; //??null追記
        $a_conditions['stock_type']      = $request->input('stock_type') ?? null; //??null追記
        $a_conditions['rate']            = $request->input('rate') ?? null; //??null追記
        $a_conditions['msd_rate']        = ($request->input('msd_rate') ?? 0);

        // 通常・赤伝集出設定
        $a_options['billpay']   = ($request->input('billpay') ?? 0);
        $a_options['billpayed'] = ($request->input('billpayed') ?? 0);

        $a_offset =  ['page' => ($request->input('page') ?? 1), 'size' => 50];

        $a_detail = $BillPayPtnModel->getDetail($a_conditions, $a_options, $a_offset);

        // 検索結果が0件のときはメッセージを表示
        if (count($a_detail['values']) < 1) {
            $errors[] = '精算対象となる予約はありませんでした。';
        }

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
            $guides[] = "精算書が作成されたパートナーはありませんでした。";
        }

        // ビューを表示
        return view('ctl.brbillpayptn.detail', [
            'form_params'         => $requestBrBillPayPtn,
            // $this->_assign->partner_cd              = $this->_s_partner_cd; //いる？？
            'customer'      => $a_customer,
            'detail' => $a_detail,
            'pager' => $pager,
            'search_params' => $_a_pager_params,

            //error,guideメッセージがない時は空の配列を返す
            'errors'    => $errors ?? [],
            'guides'    => $guides ?? []
        ]);
    }

    //======================================================================
    // CSVダウンロード
    //======================================================================
    public function csv(Request $request)
    {
        // データを取得
        $requestBrBillPayPtn = $request->all();
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
