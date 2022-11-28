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

class BrBillPayPtnController extends _commonController
{
    use Traits;

    // パートナー精算書一覧表示
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
            // $this->_request->setParam('year', $o_models_date->to_format('Y'));
            $year = $o_models_date->to_format('Y');
        }
        if ($this->is_empty($month)) {
            // $this->_request->setParam('month', $o_models_date->to_format('m'));
            $month = $o_models_date->to_format('m');
        }

        $o_billpay_ym = new DateUtil($year . '-' . $month . '-01');
        $billpayptn = $BillPayPtnModel->getBillPayPtn(array('billpay_ym' => $o_billpay_ym->to_format('Y-m')));

        $error = ''; //追記初期化、下記変更
        if ($error == 'NotFound') {
            $guide = "精算書が作成されたパートナーはありませんでした。";
        }

        // データを ビューにセット
        $this->addViewData("billpayptn", $billpayptn);
        $this->addViewData("year", $year);
        $this->addViewData("month", $month);

        // ビューを表示
        return view("ctl.brbillpayptn.list", $this->getViewData());
    }
}
