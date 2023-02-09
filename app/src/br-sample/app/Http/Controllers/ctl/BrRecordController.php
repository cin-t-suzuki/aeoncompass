<?php

namespace App\Http\Controllers\ctl;

use App\Common\Traits;
use App\Models\Record;
use App\Models\PartnerCustomer;
use App\Util\Models_Cipher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BrRecordController extends _commonController
{
    use Traits;

    // 日別一覧表示
    public function view(Request $request, $param1 = null, $param2 = null, $param3 = null, $param4 = null)
    {
        //フレームワークに組み込まれるまでの臨時対応です。
        // URI から [/]で区切りパラメータを分割する。
        //書き換えあっているか？
        // $a_params = preg_split('|/|', $this->box->info->env->uri);
        $a_params = preg_split('|/|', $request->path());

        // 先頭が空ならから削除
        if ($this->is_empty($a_params[0])) {
            $temp = array_shift($a_params);
        }
        // 末尾が空ならから削除
        if ($this->is_empty($a_params[count($a_params) - 1])) {
            $temp = array_pop($a_params);
        }

        $s_type = null; //初期化nullでいいか？

        // パラメータの取り込み
        if (count($a_params) <= 3) {
            $n_year = date('Y');
            $n_month = date('m');
        } else {
            for ($n_cnt = 3; $n_cnt < count($a_params); $n_cnt++) {
                if (strlen($a_params[$n_cnt]) == 4) {
                    $n_year = $a_params[$n_cnt];
                } elseif (strlen($a_params[$n_cnt]) == 2) {
                    $n_month = $a_params[$n_cnt];
                } elseif (in_array($a_params[$n_cnt], array('f', 'w', 'y'))) {
                    $s_type = $a_params[$n_cnt];
                } elseif (strlen($a_params[$n_cnt]) == 1) {
                    $n_month = '0' . $a_params[$n_cnt];
                }
            }
        }
        //フレームワークに組み込まれたら、入れ替えようね。

        //オブジェクト取得
        $recordModel = new Record();

        if ($this->is_empty($n_month ?? null)) { //null追記でいいか？（パラメータないときの引数null）
            if ($this->is_empty($n_year ?? null)) { //null追記でいいか？（パラメータないときの引数null）
                if ($s_type == 'y') {
                    $a_Records = $recordModel->getAllRecords();
                    $date_ymd = null; //初期化nullでいいか？
                }
            } else {
                if ($s_type == 'f') {
                    $recordModel->setDateYmd($n_year . '-04-01');
                    $a_Records = $recordModel->getFiscalRecords();
                    $date_ymd = $recordModel->getDateYmd();
                } else {
                    $s_type = 'y';
                    $recordModel->setDateYmd($n_year . '-01-01');
                    $a_Records = $recordModel->getYearRecords();
                    $date_ymd = $recordModel->getDateYmd();
                }
            }
        } else {
            if ($s_type == 'w') {
                $recordModel->setDateYmd($n_year . '-' . $n_month . '-01');
                $a_Records = $recordModel->getWeekRecords();
                $date_ymd = $recordModel->getDateYmd();
            } else {
                $s_type = 'd';
                $recordModel->setDateYmd($n_year . '-' . $n_month . '-01');
                $a_Records = $recordModel->getDayRecords();
                $date_ymd = $recordModel->getDateYmd();
            }
        }

        return view('ctl.brRecord.view', [
            'type' => $s_type,
            'records' => $a_Records,
            'date_ymd' => $date_ymd,
        ]);
    }
}
