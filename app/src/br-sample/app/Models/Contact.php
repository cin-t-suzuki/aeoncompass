<?php

namespace App\Models;

use App\Common\Traits;
use App\Models\common\CommonDBModel;
use App\Models\common\ValidationColumn;
use App\Util\Models_Cipher;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Exception;
use Illuminate\Support\Facades\Request;

class Contact extends CommonDBModel
{
    use Traits;

    public function __construct()
    {
        // カラム情報の設定
    }

    // お問い合わせ
    public function setCustomerCategorys() //protected→publicへ変更していいか？
    {
        $category_nm = '問い合わせ区分';
        $categorys = [
            '01' => '予約の確認・変更・取消に関するお問い合わせ',
            '02' => '宿泊に関するお問い合わせ',
            '03' => 'レンタカーに関するお問い合わせ',
            '04' => '会員情報に関するお問い合わせ',
            '05' => '予約手続き・プランに関するお問い合わせ',
            '06' => '料金、支払いに関するお問い合わせ',
            '07' => 'サービスに関するお問い合わせ',
            '08' => 'その他のお問い合わせ',
        ];

        return ['category_nm' => $category_nm, 'categorys' => $categorys];
    }


    // パラメータ設定
    public function setParamsCustomer($request) //リクエストを引数に追加 //protected→publicへ変更していいか？
    {
        $param['category']   = $request->input('category');
        $param['full_nm']    = $request->input('full_nm');
        $param['account_id'] = $request->input('account_id');
        $param['email']      = $request->input('email');
        $param['rsv_cd']     = $request->input('rsv_cd');
        $param['hotel_nm']   = $request->input('hotel_nm');
        $param['date_ymd']   = $request->input('date_ymd');
        $param['guest_nm']   = $request->input('guest_nm');
        $param['note'] = null;
        $a_note = mb_split("\n", $request->input('note'));

        $n_strwords = 36;
        for ($n_cnt = 0; $n_cnt < count($a_note); $n_cnt++) {
            $s_note = $a_note[$n_cnt];
            while (!$this->is_empty($s_note)) {
                $param['note'] .= trim(mb_substr($s_note, 0, $n_strwords)) . "\n";
                $s_note = trim(mb_substr($s_note, $n_strwords));
            }
        }

        return ['param' => $param];
    }
}
