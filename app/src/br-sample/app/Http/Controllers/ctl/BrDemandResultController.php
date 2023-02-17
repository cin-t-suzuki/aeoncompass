<?php

namespace App\Http\Controllers\ctl;

use App\Http\Controllers\ctl\_commonController;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use App\Models\Billpay;
use App\Common\Traits;

/**
 * 送客請求実績確認
 */
class BrDemandResultController extends _commonController
{
    use Traits;

    /**
     * 一覧
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function list(Request $request)
    {
        $billpayModel  = new Billpay();
        $keyword = $request->input('keyword');

        // condition設定　（施設コード、開始日、終了日
        $a_conditions = ['keyword'    => $keyword];

        // 検索用キーワードが入力されていれば検索を行う
        $a_search_customer = null; //初期化追記、nullでいいか
        if ((!$this->is_empty(trim($request->input('keyword'))))) {
            // 送客実績一覧取得
            $a_search_customer = $billpayModel->searchCustomer($a_conditions);

            if (count($a_search_customer) == 0) {
                $errors[] = "該当請求データが存在しませんでした。";
            }
        }

        // ビューを表示
        return view('ctl.brDemandResult.list', [
            'search_customer' => $a_search_customer,
            // hidden
            'keyword' => $keyword,
            'period' => $request->input('period'),

            //エラーメッセージが設定されていなければ空の配列を返す
            'errors' => $errors ?? []
        ]);
    }
}
